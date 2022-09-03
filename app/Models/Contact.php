<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public $table="acme_customer_contact_master";
    public $timestamps = false;
    protected $primaryKey = 'owncode';
    protected $fillable = [
        'owncode',
        'customercode', 
        'contactpersonname',
        'phoneno',
        'mobileno',
        'emailid',
        'branch'
    ];
}
