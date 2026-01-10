<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromotion extends Model
{
    use HasFactory;
    

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    // Accessor: URL Cover Image
    
}