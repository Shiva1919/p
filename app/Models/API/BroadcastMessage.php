<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BroadcastMessage extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'MessageTarget', 'PackageType', 'PackageSubType', 'GstType', 'CustomerCode', 'DateFrom', 'ToDate', 'MessageTitle', 'MessageDesc',  'Active', 'HowManyDaysToDisplay', 'AllowToMarkAsRead', 'RoleCode', 'URLString', 'SpecialKeyToClose', 'MessageDescMarathi', 'MessageDescHindi', 'MessageDescKannada', 'MessageDescGujarathi'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Broadcast Message';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Broadcast Message";
    }

    public $table="broadcast_messages";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'MessageTarget',
        'PackageType',
        'PackageSubType',
        'GstType',
        'CustomerCode',
        'DateFrom',
        'ToDate',
        'MessageTitle',
        'MessageDesc',
        'Active',
        'HowManyDaysToDisplay',
        'AllowToMarkAsRead',
        'RoleCode',
        'URLString',
        'AllPreferredLanguages',
        'SpecialKeyToClose',
        'MessageDescMarathi',
        'MessageDescHindi',
        'MessageDescKannada',
        'MessageDescGujarathi'
    ];
}
