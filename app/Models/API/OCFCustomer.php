<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCFCustomer extends Model
{
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
