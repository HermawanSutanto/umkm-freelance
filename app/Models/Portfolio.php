<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'thumbnail',
        'project_url'
    ];

    /**
     * Menggunakan slug untuk routing (misal: /portfolio/judul-proyek)
     * menggantikan ID.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi: Portfolio dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Mendapatkan URL thumbnail.
     * Menggunakan facade Storage agar kompatibel dengan Local/S3.
     */
    public function getThumbnailUrlAttribute()
    {
        // Jika ada thumbnail, generate URL publiknya
        if ($this->thumbnail) {
            // Menggunakan Storage facade lebih aman daripada hardcode path
            return Storage::url($this->thumbnail);
        }

        // Fallback image (pastikan file ini ada di public/images/)
        return asset('images/default-portfolio.png');
    }

    /**
     * Scope untuk filter kategori (Opsional, mempermudah query)
     */
    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
    }
}