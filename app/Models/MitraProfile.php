<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // 1. Gunakan Model biasa, bukan Authenticatable

class MitraProfile extends Model
{
    /** @use HasFactory<\Database\Factories\FreelancerProfileFactory> */
    use HasFactory; // 2. Hapus HasApiTokens dan Notifiable (itu milik User)

    // 3. Masukkan kolom yang ada di tabel 'freelancer_profiles' saja
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'shop_address',
        'phone_number',     
        'operational_hours', 
        'gmaps_link',
        'shop_image',
    ];
    public function getShopImageUrlAttribute()
    {
        // Cek jika field tidak kosong dan file fisiknya ada
        if ($this->shop_image && file_exists(public_path('storage/' . $this->shop_image))) {
            return asset('storage/' . $this->shop_image);
        }

        // Return gambar default/placeholder jika tidak ada gambar
        // Pastikan Anda punya gambar ini di folder public/images/
        return "https://ui-avatars.com/api/?name=" . urlencode($this->shop_name) . "&background=random";
    }
    /**
     * Relasi balik ke User (Pemilik profil ini)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}