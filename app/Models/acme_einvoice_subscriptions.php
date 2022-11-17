<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class acme_einvoice_subscriptions extends Model
{
    use HasFactory;
    protected $table='acme_einvoice_subscription';
    protected $primaryKey = 'OwnCode';
    protected $fillable =[
        'CustomerName',
        'Address',
        'StartDate',
        'ExpiryDate',
        'Gstin',
        'CreationDateTime',
        'IsActive',
        'PaymentReceived'
        ];

     public $timestamps = false;

//    protected $fillable =
//                         [
//                          'CustomerName',
//                          'Address',
//                           'Gstin',
//                           'StartDate',
//                           'ExpiryDate',
//                           'CreationDateTime',
//                           'IsActive',
//                           'PaymentReceived'
//                         ];
}
