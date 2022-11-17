<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apilog extends Model
{
    use HasFactory;
    protected $table='ApiLogTable';
    protected $primaryKey = 'id';
    protected $fillable =[
        'GSTIN',
        'apitype',
        'apidata',
        'apiresponse',
        'ApiTimeStamp',
       ];
       public $timestamps = false;
}
