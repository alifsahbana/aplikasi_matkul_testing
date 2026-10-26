<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Memeriksa apakah user memiliki peran Administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Memeriksa apakah user memiliki peran Pengunjung
     */
    public function isPengunjung(): bool
    {
        return $this->role === 'pengunjung';
    }

    /**
     * Relasi ke UMKM yang ditambahkan
     */
    public function umkms(): HasMany
    {
        return $this->hasMany(TobaccoUmkm::class, 'created_by');
    }

    /**
     * Relasi ke Riwayat Impor CSV
     */
    public function importLogs(): HasMany
    {
        return $this->hasMany(ImportLog::class);
    }
}
