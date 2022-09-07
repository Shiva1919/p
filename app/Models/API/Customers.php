<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Customers extends Model
{
    use HasFactory;
    public $table="users";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tenantcode',
        'name',
        'entrycode',
        'address1', 
        'address2', 
        'mobile', 
        'otp',
        'isverified',
        'phone',
        'gender', 
        'email', 
        'state', 
        'district', 
        'taluka', 
        'city', 
        'company_name', 
        'panno', 
        'gstno', 
        'noofbranches'
    ];

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = Crypt::encryptString($value);
    // }

    // public function setprimarymobilenoAttribute($value)
    // {
    //     $this->attributes['primarymobileno'] = Crypt::encryptString($value);
    // }

    // public function setprimaryemailidAttribute($value)
    // {
    //     $this->attributes['primaryemailid'] = Crypt::encryptString($value);
    // }

    // public function setpannoAttribute($value)
    // {
    //     $this->attributes['panno'] = Crypt::encryptString($value);
    // }

    // public function setgstnoAttribute($value)
    // {
    //     $this->attributes['gstno'] = Crypt::encryptString($value);
    // }

    // public function getNameAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getprimarymobilenoAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getprimaryemailidAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getpannoAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getgstnoAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }
}
