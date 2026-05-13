<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Paket::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pakets  = $query->orderBy('harga')->get();
        $judul   = $request->filled('kategori')
            ? 'Paket ' . $request->kategori . ' Eksklusif'
            : 'Daftar Paket Eksklusif';

        return view('customer.home', compact('pakets', 'judul'));
    }

    public function showPaket(Paket $paket)
    {
        $mediaItems = $this->buildMediaItems($paket);
        return view('customer.paket-detail', compact('paket', 'mediaItems'));
    }

    private function buildMediaItems(Paket $paket): array
    {
        $items = [];
        $galleryImages = [];
        $fallbackVideo = null;
        $localDefaultVideo = 'assets/videos/20 Detik 14 Januari.mp4';
        $defaultVideoUrl = file_exists(public_path($localDefaultVideo))
            ? $localDefaultVideo
            : 'https://www.youtube.com/watch?v=3PUKVqYLOWE';
        $dummyImages = [
            'https://images.unsplash.com/photo-1519741497674-611481863552?w=1400',
            'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=1400',
            'https://images.unsplash.com/photo-1523438885200-e635ba2c371e?w=1400',
            'https://images.unsplash.com/photo-1520854221256-17451cc331bf?w=1400',
            'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=1400',
        ];

        $cover = $this->resolveAssetUrl($paket->gambar);
        if ($cover) {
            $items[] = ['type' => 'image', 'url' => $cover];
        }

        $videoUrl = trim((string) ($paket->video_url ?? ''));
        if ($videoUrl === '') {
            $videoUrl = $defaultVideoUrl;
        }
        if ($videoUrl !== '') {
            $videoItem = $this->mapVideoUrl($videoUrl);
            if ($videoItem) {
                $items[] = $videoItem;
            }
        }

        $rawMedia = preg_split('/\r\n|\r|\n/', (string) ($paket->media_urls ?? '')) ?: [];
        foreach ($rawMedia as $url) {
            $clean = trim((string) $url);
            if ($clean === '') {
                continue;
            }

            $mapped = $this->mapMediaUrl($clean);
            if ($mapped['type'] === 'image') {
                $galleryImages[] = $mapped;
                continue;
            }

            if (!$fallbackVideo && $videoUrl === '') {
                $fallbackVideo = $mapped;
            }
        }

        if (!$videoUrl && $fallbackVideo) {
            $items[] = $fallbackVideo;
        }

        foreach ($galleryImages as $imageItem) {
            $items[] = $imageItem;
        }

        $unique = collect($items)
            ->unique(fn ($item) => ($item['type'] ?? '') . '|' . ($item['url'] ?? ''))
            ->values()
            ->all();

        $ordered = collect($unique);
        $imageItems = $ordered->where('type', 'image')->values();
        $videoItems = $ordered->reject(fn ($item) => ($item['type'] ?? '') === 'image')->values();
        $unique = $imageItems->concat($videoItems)->values()->all();

        $imageCount = collect($unique)->where('type', 'image')->count();
        $dummyIndex = 0;
        while ($imageCount < 6 && $dummyIndex < count($dummyImages)) {
            $unique[] = [
                'type' => 'image',
                'url' => $dummyImages[$dummyIndex],
            ];
            $imageCount++;
            $dummyIndex++;
        }

        if (empty($unique)) {
            $unique[] = [
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=1200',
            ];
        }

        return $unique;
    }

    private function mapMediaUrl(string $rawUrl): array
    {
        $url = $this->normalizeToUrl($rawUrl);

        if ($instagram = $this->toInstagramEmbedUrl($url)) {
            return ['type' => 'instagram', 'url' => $instagram];
        }

        if ($youtube = $this->toYoutubeEmbedUrl($url)) {
            return ['type' => 'youtube', 'url' => $youtube];
        }

        if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $url)) {
            return ['type' => 'video', 'url' => $url];
        }

        return ['type' => 'image', 'url' => $url];
    }

    private function mapVideoUrl(string $rawUrl): ?array
    {
        $url = $this->normalizeToUrl($rawUrl);

        if ($instagram = $this->toInstagramEmbedUrl($url)) {
            return ['type' => 'instagram', 'url' => $instagram];
        }

        if ($youtube = $this->toYoutubeEmbedUrl($url)) {
            return ['type' => 'youtube', 'url' => $youtube];
        }

        if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $url)) {
            return ['type' => 'video', 'url' => $url];
        }

        return null;
    }

    private function toYoutubeEmbedUrl(string $url): ?string
    {
        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]{11})#i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $matches[1] . '&controls=1';
        }

        return null;
    }

    private function toInstagramEmbedUrl(string $url): ?string
    {
        if (preg_match('#instagram\.com/(p|reel|tv)/([A-Za-z0-9_-]+)#i', $url, $matches)) {
            return 'https://www.instagram.com/' . $matches[1] . '/' . $matches[2] . '/embed/';
        }

        return null;
    }

    private function normalizeToUrl(string $value): string
    {
        $trimmed = trim($value);

        if (Str::startsWith($trimmed, ['http://', 'https://'])) {
            return $trimmed;
        }

        return asset(ltrim($trimmed, '/'));
    }

    private function resolveAssetUrl(?string $filename): ?string
    {
        $name = trim((string) $filename);
        if ($name === '') {
            return null;
        }

        $path = public_path('assets/images/' . $name);
        return file_exists($path) ? asset('assets/images/' . $name) : null;
    }
}
