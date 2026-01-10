<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user()->load(['mitraProfile', 'freelancerProfile']);
        return view('profile.edit', compact('user'));
    }

    /**
     * Memproses update data profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        // dd($request->all(),$user->role);
        // 1. Validasi Dasar (User)
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ];

        // 2. Tambahan Validasi Berdasarkan Role
        if ($user->role === 'mitra') {
            $rules = array_merge($rules, [
                'shop_name'        => 'required|string|max:255',
                'shop_description' => 'nullable|string',
                'shop_address'     => 'nullable|string',
                'phone_number'     => 'nullable|string',
                'gmaps_link'       => 'nullable|string',
                'shop_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'open_time'        => 'nullable|date_format:H:i',
                'close_time'       => 'nullable|date_format:H:i|after:open_time',
            ]);
        } elseif ($user->role === 'freelancer') {
            $rules = array_merge($rules, [
                'phone_number'     => 'nullable|string',
                'headline'       => 'nullable|string|max:100', // Judul singkat
                'bio'            => 'nullable|string|max:1000',
                'skills'         => 'required|string',
                'is_available'   => 'required|boolean', // 1 atau 0
                'portfolio_link' => 'nullable|url',
                'linkedin_url'   => 'nullable|url',
            ]);
            // dd($rules);

        }

        // Jalankan Validasi
        $validated = $request->validate($rules, [
            'close_time.after'   => 'Jam tutup harus lebih akhir dari jam buka.',
            'shop_name.required' => 'Nama Toko wajib diisi.',
            'skills.required'    => 'Keahlian (Skills) wajib diisi.',
            
        ]);
        // dd($validated);

        DB::beginTransaction();

        try {
            // 3. Update Tabel Users (Data Login)
            $user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);

            // 4. Update Profile Mitra
            if ($user->role === 'mitra') {
                dd($user->role);

                // Logic Jam Operasional
                $operationalHours = null;
                if ($request->open_time && $request->close_time) {
                    $operationalHours = $request->open_time . ' - ' . $request->close_time;
                } elseif ($user->mitraProfile?->operational_hours) {
                    $operationalHours = $user->mitraProfile->operational_hours;
                }

                // Data yang akan diupdate
                $mitraData = [
                    'shop_name'         => $request->shop_name,
                    'shop_address'      => $request->shop_address,
                    'shop_description'  => $request->shop_description,
                    'phone_number'      => $request->phone_number,
                    'operational_hours' => $operationalHours,
                    'gmaps_link'        => $request->gmaps_link,
                ];

                // Logic Upload Gambar (Hanya update array jika ada file baru)
                // Ini mencegah gambar lama tertimpa NULL jika user tidak upload baru
                if ($request->hasFile('shop_image')) {
                    $mitraData['shop_image'] = $this->uploadImage($request->file('shop_image'), 'shops/covers');
                }

                $user->mitraProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $mitraData
                );
            } 
            
            // 5. Update Profile Freelancer
            elseif ($user->role === 'freelancer') {
                // dd($user->role,$request);

                $user->freelancerProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'headline'       => $request->headline,
                        'bio'            => $request->bio,
                        'skills'         => $request->skills,
                        'is_available'   => $request->is_available,
                        
                        // Tautan
                        'portfolio_link' => $request->portfolio_link, // Pastikan nama kolom di DB sesuai
                        'linkedin_url'   => $request->linkedin_url,
                        
                        // Note: Kolom 'hourly_rate' dihapus dari request karena tidak dipakai di view
                    ]
                );
            }

            DB::commit();

            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show()
    {
        $user = Auth::user()->load(['mitraProfile', 'freelancerProfile']);
        return view('profile.show', compact('user'));
    }

    private function uploadImage($file, $path)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        
        // Resize agar tidak terlalu berat (max width 800px cukup untuk cover toko)
        $image->scaleDown(width: 800);
        $encoded = $image->toWebp(quality: 80);

        $filename = Str::random(40) . '.webp';
        $fullPath = $path . '/' . $filename;

        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }
}