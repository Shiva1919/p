<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Contacts extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['customercode', 'contactpersonname', 'phoneno','mobileno', 'emailid', 'branch'];
    
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Contacts';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Contacts";
    }

    public $table="acme_contact";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tenantcode',
        'customercode', 
        'contactpersonname',
        'phoneno',
        'mobileno',
        'emailid',
        'branch'
    ];
}
