<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    public $table="acme_package_type_master";
    public $timestamps = false;
    protected $primaryKey = 'owncode';
    protected $fillable = [
        'owncode',
        'name',
        'description'
    ];
}
