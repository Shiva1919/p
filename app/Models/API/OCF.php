<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCF extends Model
{
    use HasFactory;
    public $table="ocf_master";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customercode',
        'companycode',
        'Series',
        'DocNo',
        'ocf_date',
        'AmountTotal',
      ];
}
