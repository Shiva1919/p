<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCFModule extends Model
{
    use HasFactory;
    public $table="ocf_modules";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'ocfcode',
        'modulecode',
        'modulename',
        'moduletypes',
        'quantity',
        'unit',
        'expirydate',
        'activation',
        'amount',
        'total',
    ];


}
