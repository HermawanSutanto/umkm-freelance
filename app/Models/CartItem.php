<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price_at_purchase'];
    public function cart(){
        return $this->belongsTo(Cart::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function getSubTotalAttribute(){
        return $this->quantity*$this->price_at_purchase;
    }
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}