<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msg_history extends Model
{
    use HasFactory;
    protected $table='msg_historys';
    protected $fillable =[
        'cust_id',
        'emp_id',
        'url',
        'state_id',
        'district_id',
        'taluka_id',
        'city_id',
        'created_at'
    ];
}
