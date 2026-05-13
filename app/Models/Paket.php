<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';
    protected $primaryKey = 'paket_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_paket',
        'kategori',
        'paket_type',
        'bundling_name',
        'duration_type',
        'deskripsi',
        'harga',
        'gambar',
        'media_urls',
        'video_url',
    ];

    protected $casts = [
        'harga' => 'decimal:0',
    ];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'paket_id', 'paket_id');
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->harga, 0, ',', '.');
    }

    public function getDetailPointsAttribute(): array
    {
        $raw = trim(strip_tags((string) $this->deskripsi));
        if ($raw === '') {
            return [];
        }

        $items = preg_split('/\r\n|\r|\n|•|\||,/', $raw) ?: [];

        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * PAKET TYPE HELPER METHODS
     */

    /**
     * Check apakah paket adalah wedding only
     */
    public function isWeddingOnly(): bool
    {
        return $this->paket_type === 'wedding_only';
    }

    /**
     * Check apakah paket adalah prewedd only
     */
    public function isPreweddOnly(): bool
    {
        return $this->paket_type === 'prewedd_only';
    }

    /**
     * Check apakah paket adalah all-in
     */
    public function isAllIn(): bool
    {
        return $this->paket_type === 'all_in';
    }

    /**
     * Get bundling info
     */
    public function getBundlingInfo(): ?string
    {
        if (!$this->isAllIn() || !$this->bundling_name) {
            return null;
        }

        $duration = $this->duration_type ? " ({$this->duration_type})" : '';
        return "{$this->bundling_name}{$duration}";
    }

    /**
     * Get tentang termin - berapa termin untuk paket ini
     * Wedding Only / Prewedd Only = 2 termin
     * All In = 3 termin
     */
    public function getTerminCount(): int
    {
        return $this->isAllIn() ? 3 : 2;
    }
}
