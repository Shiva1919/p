<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serialno extends Model
{
    use HasFactory;
    public $table="serialno";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'ocfno', 
        'transaction_datetime',
        'serialno_issue_date', 
        'serialno_validity', 
        'serialno_parameters', 
        'serialno'
    ];
}
