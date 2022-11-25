<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class OCFCustomer extends Model
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected static $logAttributes = [ 'name', 'entrycode', 'address1', 'address2', 'otp', 'isverified', 'phone', 'whatsappno', 'email', 'state', 'district', 'taluka', 'city', 'role_id', 'noofbranch', 'password', 'concernperson', 'packagename','subpackagecode', 'customercode'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Customer';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Customer";
    }
    use HasFactory;
    public $table="customer_master";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tenantcode',
        'name',
        'entrycode',
        'address1',
        'address2', 
        'otp',
        'isverified',
        'phone',
        'whatsappno',
        'email', 
        'state', 
        'district', 
        'taluka', 
        'city', 
        'role_id',
        'noofbranch',
        'password',
        'concernperson',
        'packagename',
        'subpackagecode',
        'customercode'
    ];
}
