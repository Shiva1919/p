<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Customer_mobile_Model extends Model
{
    use HasFactory;
    public $table="Customer_mobilenumbers";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'Mobile_number',
        'Email',
        'User_Name',
        'Customercode',
  ];
}
