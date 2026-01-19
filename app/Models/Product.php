<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'user_id','category_id', 'name', 'slug', 'description', 'real_price',
        'price', 'stock', 'cover_image', 'is_active','highlight_level','highlight_expires_at'
    ];
    protected $appends = ['cover_url', 'price_format'];

    // Relasi: 1 Produk punya BANYAK Gambar Galeri
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: URL Cover Image
    public function getCoverUrlAttribute()
    {
        if ($this->cover_image && file_exists(public_path('storage/' . $this->cover_image))) {
            return asset('storage/' . $this->cover_image);
    }
        return asset('images/default-product.png'); 
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Accessor: Format Rupiah
    public function getPriceFormatAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    public function getRealPriceFormatAttribute()
    {
        return 'Rp ' . number_format($this->real_price, 0, ',', '.');
    }
    public function promotions(){
        return $this->hasMany(ProductPromotion::class);
    }
}