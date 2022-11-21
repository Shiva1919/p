<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class OCFCustomer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    public $table="customer_master";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tenantcode',
        'name',
        'entrycode',
        'address1',
        'address2', 
        'otp',
        'isverified',
        'phone',
        'email', 
        'state', 
        'district', 
        'taluka', 
        'city', 
        'role_id',
        'noofbranch',
        'password',
        'concernperson',
        'packagename',
        'subpackagecode',
        'customercode'
    ];
}
