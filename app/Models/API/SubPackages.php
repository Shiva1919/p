<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPackages extends Model
{
    use HasFactory;
    public $table="acme_subpackage";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'packagetype'
    ];
}
