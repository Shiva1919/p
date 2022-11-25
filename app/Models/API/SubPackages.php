<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubPackages extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['subpackagename','description', 'active','packagetype'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Subpackage';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Subpackage";
    }
    
    public $table="acme_subpackage";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'subpackagename',
        'description',
        'active',
        'packagetype'
    ];
}
