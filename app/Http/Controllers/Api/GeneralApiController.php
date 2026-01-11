<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralApiController extends Controller
{
    public function getAdminContact()
    {
        // 1. Cari User dengan role 'admin' yang pertama ditemukan
        $admin = User::where('role', 'admin')
                    ->with('adminProfile') // Pastikan relasi ini ada di Model User
                    ->first();

        // Default nomor jika admin tidak ditemukan (Fallback)
        $phoneNumber = '6281234567890'; 

        if ($admin) {
            // Cek apakah ada di adminProfile (jika ada tabel khusus)
            if ($admin->adminProfile && $admin->adminProfile->phone_number) {
                $phoneNumber = $admin->adminProfile->phone_number;
            } 
            // Atau cek langsung di tabel users jika kolom phone_number ada di sana
            elseif ($admin->phone_number) {
                $phoneNumber = $admin->phone_number;
            }
        }

        // 2. Format Nomor HP (Ubah 08xx jadi 628xx untuk link WA)
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'whatsapp_number' => $phoneNumber,
                'name' => $admin ? $admin->name : 'Admin',
                'full_link' => "https://wa.me/{$phoneNumber}?text=Halo%20Admin%20GriyaKarya,%20saya%20butuh%20bantuan."
            ]
        ]);
    }
}