<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $table="company_master";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customercode',
        'company_name',
        'pan_no',
        'gst_no',
        'InstallationType',
        'InstallationDesc'
    ];
}
