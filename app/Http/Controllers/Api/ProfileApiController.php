<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProfileApiController extends Controller
{
    /**
     * GET /api/profile
     * Menampilkan data user beserta profile customer-nya
     */
    public function show()
    {
        $user = Auth::user();
        
        // Eager load customer profile
        $user->load('customerProfile');

        return response()->json([
            'message' => 'Data profil berhasil diambil',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                // Ambil data profile atau null jika belum ada
                'profile' => $user->customerProfile 
            ]
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/profile
     * Update data user dan customer profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'city'         => 'required|string|max:100',
            'province'     => 'required|string|max:100',
            'postal_code'  => 'required|string|max:10',
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:L,P', // L = Laki-laki, P = Perempuan
        ]);

        DB::beginTransaction();
        try {
            // 2. Update Data User Utama (Tabel Users)
            $user->update([
                'name' => $validated['name']
            ]);

            // 3. Update atau Buat Customer Profile (Tabel customer_profile)
            // updateOrCreate akan mencari berdasarkan 'user_id', jika ada diupdate, jika tidak dibuat baru
            $user->customerProfile()->updateOrCreate(
                ['user_id' => $user->id], // Kondisi pencarian
                [
                    'phone_number' => $validated['phone_number'],
                    'address'      => $validated['address'],
                    'city'         => $validated['city'],
                    'province'     => $validated['province'],
                    'postal_code'  => $validated['postal_code'],
                    'birth_date'   => $validated['birth_date'],
                    'gender'       => $validated['gender'],
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'data' => $user->load('customerProfile')
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}