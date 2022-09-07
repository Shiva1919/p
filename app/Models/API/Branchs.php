<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchs extends Model
{
    use HasFactory;
    public $table="acme_branch";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'customercode', 
        'branchname',
        'branchaddress1', 
        'branchaddress2', 
        'branchstate', 
        'branchdistrict', 
        'branchtaluka', 
        'branchcity'
    ];
}
