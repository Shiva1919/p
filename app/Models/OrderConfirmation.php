<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConfirmation extends Model
{
    use HasFactory;
    public $table="acme_customer_products";
    public $timestamps = false;
    protected $primaryKey = 'owncode';
    protected $fillable = [
        'owncode',
        'salestype',
        'packagetype',
        'packagesubtype',
        'fromdate', 
        'todate', 
        'purchasedate',
        'ocfno',
        'eefOcfnocode',
        'initialusercount',
        'validityperiodofinitialusers',
        'customercode', 
        'branchcode', 
        'concernperson', 
        'narration'
    ];
}
