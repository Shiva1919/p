<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    use HasFactory;
    public $table="acme_module";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'parentproductcode',
        'name',
        'description', 
        'price',
        'unit',
        'producttype'
    ];
}
