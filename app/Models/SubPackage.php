<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPackage extends Model
{
    use HasFactory;
    public $table="acme_package_subtype_master";
    public $timestamps = false;
    protected $primaryKey = 'Owncode';
    protected $fillable = [
        'name',
        'description',
        'packagetype'
    ];
}
