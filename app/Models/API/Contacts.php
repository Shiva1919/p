<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    public $table="acme_contact";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tenantcode',
        'customercode', 
        'contactpersonname',
        'phoneno',
        'mobileno',
        'emailid',
        'branch'
    ];
}
