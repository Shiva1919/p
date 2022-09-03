<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    public $table="acme_customer_branches_master";
    public $timestamps = false;
    protected $primaryKey = 'owncode';
    protected $fillable = [
        'owncode', 
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
