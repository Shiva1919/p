<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OCFModule extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'ocfcode','modulecode', 'modulename','moduletypes','quantity','unit','expirydate','activation','amount','total'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Modules';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Modules";
    }

    public $table="ocf_modules";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'ocfcode',
        'modulecode',
        'modulename',
        'moduletypes',
        'quantity',
        'unit',
        'expirydate',
        'activation',
        'amount',
        'total',
    ];


}
