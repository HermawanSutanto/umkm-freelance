<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // 1. Gunakan Model biasa, bukan Authenticatable

class AdminProfile extends Model
{
    /** @use HasFactory<\Database\Factories\FreelancerProfileFactory> */
    use HasFactory; // 2. Hapus HasApiTokens dan Notifiable (itu milik User)

    // 3. Masukkan kolom yang ada di tabel 'freelancer_profiles' saja
    protected $fillable = [
        'user_id',
        'phone_number',       // Baru
         
    ];
    protected $casts = [
        'is_available' => 'boolean',
        ];

    /**
     * Relasi balik ke User (Pemilik profil ini)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}