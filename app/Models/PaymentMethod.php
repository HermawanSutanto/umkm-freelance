<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name', 'account_number', 'account_holder',
        'logo','type', 'instructions', 'admin_fee', 'is_active'
    ];

    // PENTING: Agar 'logo_url' otomatis muncul di JSON
    protected $appends = ['logo_url'];
    protected static function booted()
    {
        static::creating(function ($paymentMethod) {
            // Cek apakah user sedang login
            if (Auth::check()) {
                // Jika user_id belum diisi manual, isi dengan ID user yang sedang login
                if (empty($paymentMethod->user_id)) {
                    $paymentMethod->user_id = Auth::id();
                }
            }
        });
    }
    // Accessor: URL Logo
    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('storage/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        // Pastikan Anda punya gambar default di folder public/images
        return asset('images/default-payment.png'); 
    }
    // Optional: Definisikan relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}