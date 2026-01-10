<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function mitraProfile()
    {
        return $this->hasOne(MitraProfile::class);
    }

    public function freelancerProfile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }
    public function customerProfile(){
        return $this->hasOne(CustomerProfile::class);
    }
    public function adminProfile(){
        return $this->hasOne(AdminProfile::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function cart(){
        return $this->hasOne(Cart::class);

    }
    public function portfolios() {
        return $this->hasMany(Portfolio::class);
    }
    public static function registerUser(array $data)
    {
        DB::beginTransaction();
        try {
            $user=self::create([
                'name'=> $data['name'],
                'email'=> $data['email'],
                'password'=>Hash::make($data['password']),
                'role'=> $data['role'],
            ]);
            if ($data['role'] === 'mitra') {
                $user->mitraProfile()->create([
                    'shop_name'=>$data[ 'shop_name']??null,
                    'shop_address'=>$data['shop_address']??null,
                ]);

                }
            elseif($data['role']==='freelancer'){
                $user->freelancerProfile()->create([
                    'skills'=>$data['skills']??null,
                    'portofolio_link'=>$data['portofolio_link']??null,
                ]);
                }
            elseif($data['role']==='customer'){
            $user->customerProfile()->create([
                'phone_number'=>$data['phone_number']??null,
                'address'=>$data['address']??null,
                'birth_date'=>$data['birth_date']??null,
                'gender'=>$data['gender']??null,
                'city'=>$data['city']??null,
                'province'=>$data['province']??null,
                'postal_code'=>$data['postal_code']??null,

            ]);
            
            }
            Db::commit();
            return $user;

        } catch (Exception $e) {
            DB::rollBack();
            
            throw $e;
        }
    }
}   
