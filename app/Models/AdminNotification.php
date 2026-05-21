<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'layout',
        'type',
        'ref_type',
        'ref_id',
        'title',
        'subtitle',
        'time_label',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /* ── Scopes ── */
    public function scopeForLayout($query, string $layout)
    {
        return $query->where('layout', $layout);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /* ── Helpers ── */

    /**
     * Sync notif dari array item. Tambah yang belum ada, tidak duplikat.
     * Item = ['ref_type','ref_id','layout','type','title','subtitle','time_label','url']
     */
    public static function syncItems(array $items): void
    {
        foreach ($items as $item) {
            static::firstOrCreate(
                [
                    'layout'   => $item['layout'],
                    'ref_type' => $item['ref_type'],
                    'ref_id'   => $item['ref_id'],
                    'type'     => $item['type'],
                ],
                [
                    'title'      => $item['title'],
                    'subtitle'   => $item['subtitle'] ?? null,
                    'time_label' => $item['time_label'] ?? null,
                    'url'        => $item['url'],
                    'is_read'    => false,
                ]
            );
        }
    }

    /**
     * Hapus notif yang sudah tidak relevan (ref_id tidak ada di $activeIds).
     */
    public static function pruneStale(string $layout, string $type, array $activeIds): void
    {
        static::where('layout', $layout)
            ->where('type', $type)
            ->whereNotIn('ref_id', $activeIds)
            ->delete();
    }
}