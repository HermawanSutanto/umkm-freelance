<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // 1. Gunakan Model biasa, bukan Authenticatable

class FreelancerProfile extends Model
{
    /** @use HasFactory<\Database\Factories\FreelancerProfileFactory> */
    use HasFactory; // 2. Hapus HasApiTokens dan Notifiable (itu milik User)

    // 3. Masukkan kolom yang ada di tabel 'freelancer_profiles' saja
    protected $fillable = [
        'user_id',
        'headline',       // Baru
        'bio',            // Baru
        'skills',
        'portfolio_link',
        'linkedin_url',   // Baru
        'cv_file',        // Baru
        'is_available', 
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