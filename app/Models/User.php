<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'role',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * ROLE HELPER METHODS
     */

    public function isCeo(): bool
    {
        return strtoupper($this->role) === 'CEO';
    }

    public function isAdmin(): bool
    {
        return strtoupper($this->role) === 'ADMIN';
    }

    public function isAdmin2(): bool
    {
        return strtoupper($this->role) === 'ADMIN2';
    }

    public function isSekretaris(): bool
    {
        return strtoupper($this->role) === 'SEKRETARIS';
    }

    /**
     * Check apakah user adalah CEO atau ADMIN (executive)
     */
    public function isExecutive(): bool
    {
        return $this->isCeo() || $this->isAdmin();
    }

    /**
     * Check apakah user bisa lihat semua bookings
     */
    public function canViewAllBookings(): bool
    {
        return $this->isExecutive() || $this->isAdmin2() || $this->isSekretaris();
    }

    /**
     * Check apakah user bisa approve/reject bookings
     */
    public function canApproveBookings(): bool
    {
        return $this->isExecutive();
    }

    /**
     * Check apakah user bisa kirim follow-up notifikasi
     */
    public function canSendFollowUp(): bool
    {
        return $this->isSekretaris() || $this->isAdmin2() || $this->isExecutive();
    }

    /**
     * Relationship ke Notifications (notifikasi yang diterima user)
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function getAvatarInitialAttribute(): string
    {
        $name = trim((string) $this->nama_lengkap);

        if ($name === '') {
            return 'A';
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $initial = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initial .= strtoupper(substr($part, 0, 1));
        }

        return $initial !== '' ? $initial : 'A';
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        return asset($this->profile_photo);
    }
}
