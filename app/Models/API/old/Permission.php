<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'name', 'display_name', 'active'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Permission';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Permission";
    }

    protected $table = 'permissionsss';
    protected $fillable = [
        'name', 'display_name', 'active'
    ];
}
