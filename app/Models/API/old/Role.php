<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['name', 'permission_id', 'active'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Role';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Role";
    }

    protected $table = 'roles';
    protected $fillable = [
        'name',
        'permission_id',
        'active'
    ];
}
