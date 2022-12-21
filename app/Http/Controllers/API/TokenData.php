<?php



namespace App\Models\API;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\personal_access_token



class TokenData extends Model

{

    use HasFactory;

    protected $table = 'personal_access_tokens';

    public $timestamps = false;

    protected $fillable = [

        'token'

    ];

}

