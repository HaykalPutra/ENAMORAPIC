<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:300'],
        ]);

        $message = trim($data['message']);
        if ($this->isGreeting($message)) {
            return response()->json([
                'reply' => 'Halo! Aku Mora. Ada yang bisa aku bantu seputar paket, budget, outfit, atau jadwal? 😊',
            ]);
        }

        $budget = $this->parseBudget($message);
        if ($budget !== null) {
            $recommendation = $this->recommendPackagesByBudget($message, $budget);
            return response()->json($recommendation);
        }
        if (!$this->isEnamorapicTopic($message)) {
            return response()->json([
                'reply' => 'Maaf, aku hanya bisa bantu pertanyaan yang relevan dengan Enamorapic (wedding, prewedding, engagement, paket, budget, outfit, dan booking).',
            ]);
        }

        $apiKey = (string) config('services.groq.api_key');
        if ($apiKey === '') {
            return response()->json([
                'reply' => 'Layanan AI belum dikonfigurasi. Silakan hubungi CS untuk bantuan cepat.',
            ], 503);
        }

        $packages = Paket::query()
            ->select(['nama_paket', 'kategori', 'paket_type', 'harga', 'deskripsi'])
            ->orderBy('harga')
            ->get()
            ->map(function (Paket $paket) {
                $points = collect($paket->detail_points)->take(3)->values()->all();
                return [
                    'nama' => $paket->nama_paket,
                    'kategori' => $paket->kategori,
                    'tipe' => $paket->paket_type,
                    'harga' => number_format((float) $paket->harga, 0, ',', '.'),
                    'highlight' => $points,
                ];
            })
            ->values()
            ->all();

        $packageText = collect($packages)->map(function ($p) {
            $highlight = empty($p['highlight']) ? '' : ('; highlight: ' . implode(', ', $p['highlight']));
            return "- {$p['nama']} ({$p['kategori']}, {$p['tipe']}, Rp {$p['harga']}){$highlight}";
        })->implode("\n");

        $systemPrompt = implode("\n", [
            'You are the AI assistant for Enamorapic Photography.',
            'Only answer questions related to Enamorapic services: wedding, prewedding, engagement, photo/video, style/pose requests, packages, budget, booking, DP/termin, outfits, locations, schedules, and timelines.',
            'If the question is outside this scope, politely refuse and invite the user to ask about Enamorapic services.',
            'Use short, friendly Indonesian responses. Avoid speculation. If unsure, suggest contacting CS.',
            'Do not use Markdown formatting (no **, *, or bullet numbering like 1.).',
            'When asked about budget or package fit, recommend 1-3 packages by name and price only. Do not add arithmetic or total calculations.',
            'When asked about outfits, give practical suggestions based on theme, location, and time of day.',
            'Packages list for reference:',
            $packageText !== '' ? $packageText : '- (Paket belum tersedia di database)',
        ]);

        $payload = [
            'model' => (string) config('services.groq.model', 'llama-3.1-8b-instant'),
            'temperature' => 0.3,
            'max_tokens' => 420,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $message],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', $payload);

        if ($response->failed()) {
            return response()->json([
                'reply' => 'Maaf, AI sedang bermasalah. Silakan coba lagi atau hubungi CS.',
            ], 502);
        }

        $reply = data_get($response->json(), 'choices.0.message.content');
        if (!is_string($reply) || trim($reply) === '') {
            return response()->json([
                'reply' => 'Maaf, AI belum memberi jawaban. Silakan coba pertanyaan lain atau hubungi CS.',
            ], 502);
        }

        return response()->json([
            'reply' => $this->sanitizeReply($reply),
        ]);
    }

    private function isEnamorapicTopic(string $text): bool
    {
        $t = strtolower($text);
        if (preg_match('/\b(ulangi|ulang|repeat|lagi)\b/', $t)) {
            return true;
        }
        return (bool) preg_match('/(enamorapic|wedding|prewedd|pre-wedd|prewedding|engagement|paket|budget|harga|outfit|lokasi|jadwal|booking|dp|termin|foto|video|pose|gaya|request|req|tema|konsep|styling|stylist)/', $t);
    }

    private function isGreeting(string $text): bool
    {
        $t = strtolower($text);
        return (bool) preg_match('/\b(halo|hai|hi|hello|assalamualaikum|pagi|siang|sore|malam)\b/', $t);
    }

    private function sanitizeReply(string $text): string
    {
        $clean = preg_replace('/\*\*?/', '', $text) ?? $text;
        $clean = preg_replace('/\s*=\s*Rp\s*[0-9\.]+/i', '', $clean) ?? $clean;
        $clean = preg_replace('/^[ \t]*\d+\.[ \t]*/m', '', $clean) ?? $clean;
        return trim((string) $clean);
    }

    private function parseBudget(string $text): ?int
    {
        $t = strtolower($text);
        if (!preg_match('/(budget|harga|biaya|rp|rupiah)/', $t)) {
            return null;
        }

        if (preg_match('/(\d+[\.,]?\d*)\s*(jt|juta)/', $t, $m)) {
            $num = str_replace(',', '.', $m[1]);
            $val = (float) $num;
            return (int) round($val * 1000000);
        }

        if (preg_match('/\d{1,3}(?:[\.,]\d{3})+/', $t, $m)) {
            $raw = str_replace([',', '.'], '', $m[0]);
            return is_numeric($raw) ? (int) $raw : null;
        }

        if (preg_match('/\b\d{6,}\b/', $t, $m)) {
            return (int) $m[0];
        }

        return null;
    }

    private function recommendPackagesByBudget(string $message, int $budget): array
    {
        $t = strtolower($message);
        $needsAllIn = preg_match('/(wedding).*(pre|prewedd|pre-wedd|prewedding)|(pre|prewedd|pre-wedd|prewedding).*(wedding)/', $t) === 1;

        $query = Paket::query()->select(['paket_id', 'nama_paket', 'kategori', 'paket_type', 'harga', 'deskripsi', 'gambar']);
        if ($needsAllIn) {
            $query->where('paket_type', 'all_in');
        }

        $items = $query->where('harga', '<=', $budget)->orderBy('harga', 'desc')->take(3)->get();
        if ($items->isEmpty()) {
            $items = $query->orderBy('harga')->take(3)->get();
        }

        $list = $items->map(function (Paket $paket) {
            $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800&q=80';
            $imgPath = public_path('assets/images/' . ($paket->gambar ?? ''));
            $imgUrl = ($paket->gambar && file_exists($imgPath)) ? asset('assets/images/' . $paket->gambar) : $fallback;
            $desc = Str::limit(trim(strip_tags((string) $paket->deskripsi)), 80, '...');
            return [
                'id' => $paket->paket_id,
                'nama' => $paket->nama_paket,
                'kategori' => $paket->kategori,
                'harga' => number_format((float) $paket->harga, 0, ',', '.'),
                'img' => $imgUrl,
                'desc' => $desc,
            ];
        })->values();

        $lines = $list->map(function ($p) {
            $desc = $p['desc'] !== '' ? " - {$p['desc']}" : '';
            return "{$p['nama']}{$desc}. Harga Rp {$p['harga']}";
        })->implode("\n");

        $budgetText = number_format($budget, 0, ',', '.');
        $reply = "Rekomendasi paket untuk budget sekitar Rp {$budgetText}:\n{$lines}";

        return [
            'reply' => $reply,
            'packages' => $list,
        ];
    }
}
