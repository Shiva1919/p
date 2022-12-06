<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCFchange extends Model
{
    use HasFactory;
    public $table="acme_ocf_change";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'ocfno',
        'companyname',
        'panno', 
        'gstno',
        'status',
        'passedby',
        'created_at',
        'updated_at'
    ];
}
