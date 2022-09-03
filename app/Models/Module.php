<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    public $connection = "mysql_2";
    public $table="master_products";
    public $timestamps = false;
    protected $primaryKey = 'ProductId';
    protected $fillable = [
        'ProductId',
        'ProductCode',
        'ProductName',
        'ProductDescription', 
        'ProductType'
    ];
}
