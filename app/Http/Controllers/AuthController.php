<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MitraProfile;      // Pastikan Model ini ada
use App\Models\FreelancerProfile; // Pastikan Model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- REGISTER ---

    // 1. Tampilkan Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 2. Proses Data
    public function register(Request $request)
    {   
        // Validasi
        $validated = $request->validate([
            "name"            => "required|string|max:255",
            "email"           => "required|email:rfc,dns|unique:users,email",
            "password"        => "required|min:8", 
            "role"            => "required|in:mitra,freelancer",
            "shop_name"       => "required_if:role,mitra|nullable|string|max:255",
            "shop_address"    => "nullable|string",
            "skills"          => "required_if:role,freelancer|nullable|string",
            "portofolio_link" => "nullable|url"
        ], [
            'shop_name.required_if' => 'Nama Toko wajib diisi untuk Mitra.',
            'skills.required_if'    => 'Keahlian wajib diisi untuk Freelancer.',
        ]);

        DB::beginTransaction(); // Mulai transaksi database agar aman
        try {
            // 1. Buat User Utama
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => $validated['role'],
            ]);

            // 2. Buat Profil Berdasarkan Role
            if ($validated['role'] === 'mitra') {
                // Simpan ke tabel mitra_profiles (sesuaikan nama model Anda)
                // Jika Anda menggunakan relasi hasOne di User Model:
                $user->mitraProfile()->create([
                    'shop_name'    => $request->shop_name,
                    'shop_address' => $request->shop_address,
                ]);
            } elseif ($validated['role'] === 'freelancer') {
                // Simpan ke tabel freelancer_profiles
                $user->freelancerProfile()->create([
                    'skills'          => $request->skills,
                    'portofolio_link' => $request->portofolio_link,
                ]);
            }

            DB::commit(); // Simpan perubahan

            // 3. Auto Login setelah register
            Auth::login($user);

            // 4. Redirect ke Dashboard
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            // Kembali ke form dengan pesan error
            return back()->withInput()->withErrors(['email' => 'Gagal mendaftar: ' . $e->getMessage()]);
        }
    }

    // --- LOGIN ---

    // 1. Tampilkan Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Coba Login menggunakan Session Laravel (Auth::attempt)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Keamanan: Regenerasi Session ID untuk mencegah Session Fixation
            $request->session()->regenerate();

            // Redirect ke halaman yang dituju user sebelumnya, atau ke dashboard
            return redirect()->intended('dashboard');
        }

        // Jika Gagal
        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidasi session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}