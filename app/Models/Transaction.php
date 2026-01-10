<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_code',
        'total_price',
        'shipping_price',
        'grand_total',
        'payment_method',
        'payment_proof', // Field baru
        'shipping_address',
        'courier',
        'service',
        'resi_number',
        'status'
    ];

    // Relasi ke Pembeli
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Item Barang
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Accessor: URL Bukti Transfer
    // Cara panggil di JSON: $transaction->payment_proof_url
    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof) {
            return url('storage/' . $this->payment_proof);
        }
        return null;
    }

    // Accessor: Format Rupiah Total
    public function getGrandTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }
}