<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConfirmations extends Model
{
    use HasFactory;
    public $table="acme_product_ocf";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'salestype',
        'packagetype',
        'packagesubtype',
        'moduleid',
        'fromdate', 
        'todate', 
        'purchasedate',
        'ocfno',
        'eefocfnocode',
        'initialusercount',
        'validityperiodofinitialusers',
        'customercode', 
        'branchcode', 
        'concernperson', 
        'narration'
    ];
}
