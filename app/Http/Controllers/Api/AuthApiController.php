<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    //
    public function register(Request $request){
        
        $request['role']='customer';
        $validated= $request->validate([
            "name" => "required|string",
            "email"=> "required|email|unique:users",
            "password"=> "required|min:8",
            "role"  => "required|in:customer",
            "phone_number"=>"required|min:11",
            "address"=>"required",
            "birth_date"=>"required",
            "gender"=>"required",
            "city"=>"required",
            "province"=>"required",
            "postal_code"=>"required",
        ]);
        try {
            Log::info($request);

          
            $user = User::registerUser($validated);
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->customerProfile()->create([
                    "phone_number"=>$request->phone_number,
                    "address"=>$request->address,
                    "birth_date"=>$request->birth_date,
                    "gender"=>$request->gender,
                    "city"=>$request->city,
                    "province"=>$request->province, 
                    "postal_code"=>$request->postal_code,
                ]);
            return response()->json([
                'message'=> 'Registrasi Berhasil',
                'token'=> $token,
                'user'=> $user->load('customerProfile')
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal register: ' . $e->getMessage()], 500);
            //throw $th;
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 1. Cari User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 2. Cek Password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // 3. Hapus token lama (Opsional: agar 1 device 1 login)
        // $user->tokens()->delete(); 

        // 4. Buat Token Baru
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Load Profile Sesuai Role (Eager Loading Dinamis)
        if ($user->role === 'mitra') {
            $user->load('mitraProfile');
        } elseif ($user->role === 'freelancer') {
            $user->load('freelancerProfile');
        } elseif ($user->role === 'customer') {
            $user->load('customerProfile');
        }

        return response()->json([
            'message' => 'Login berhasil',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * LOGOUT
     * Menghapus token yang sedang digunakan.
     */
    public function logout(Request $request)
    {
        // Menghapus token saat ini (current access token)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
