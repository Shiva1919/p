<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastMessage extends Model
{
    use HasFactory;
    public $table="broadcast_messages";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'MessageType', 
        'CustomerCode',
        'DateFrom', 
        'ToDate', 
        'Description', 
        'Active'
    ];
}
