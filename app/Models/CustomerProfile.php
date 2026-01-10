<?php
namespace   App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CustomerProfile extends Model{    
     /** @use HasFactory<\Database\Factories\FreelancerProfileFactory> */
    use HasFactory; // 2. Hapus HasApiTokens dan Notifiable (itu milik User)
    protected $table = "customer_profile";
    protected $fillable = [
        "user_id",
        "phone_number",
        "address",
        "birth_date",
        "gender",
        "city",
        "province", 
        "postal_code",
    ] ;                 
    public function user(){
        return $this->belongsTo(User::class);
    }
}