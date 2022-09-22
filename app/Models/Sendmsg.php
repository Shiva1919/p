<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sendmsg extends Model
{
    use HasFactory;
    protected $fillable =[
        'id',
        'contact_number',
        'cust_name',
        'cust_email',
        'cust_address',
        'state_id',
        'district_id',
        'taluka_id',
        'city_id',
        'post_code'
    ];

}
