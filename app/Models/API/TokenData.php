<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenData extends Model
{
    use HasFactory;
    protected $table = 'personal_access_tokens';
    public $timestamps = false;
    protected $fillable = [
        'token'
    ];
}
