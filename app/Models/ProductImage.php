<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_path'];

    // Relasi balik ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Accessor helper untuk URL
    public function getImageUrlAttribute()
    {
         return asset('storage/' . $this->image_path);
    }
}