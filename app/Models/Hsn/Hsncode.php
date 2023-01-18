<?php

namespace App\Models\Hsn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hsncode extends Model
{
    use HasFactory;
    protected $connection = 'mysql_2';
    protected $table='HsnCodesTable';
    protected $primaryKey = 'OwnCode';
    protected $fillable =[
        'hsncode',
        'hsndesc',
        'hsntype',
        'hsncodestring',
       ];
       public $timestamps = false;
}
