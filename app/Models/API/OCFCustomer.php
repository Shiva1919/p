<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;


class OCFCustomer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $auditTimestamps = true;

    protected static $logAttributes = [ 'name', 'entrycode', 'address1', 'address2', 'otp', 'isverified', 'phone', 'whatsappno', 'email', 'state', 'district', 'taluka', 'city', 'role_id', 'noofbranch', 'password', 'concernperson', 'packagename','subpackagecode', 'customercode', 'customerlanguage'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Customer';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Customer";
    }

    public $table="customer_master";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        // 'tenantcode',
        'name',
        'entrycode',
        'address1',
        'address2',
        'otp',
        'serialotp',
        'isverified',
        'phone',
        'whatsappno',
        'email',
        'state',
        'district',
        'taluka',
        'city',
        'role_id',
        // 'noofbranch',
        'password',
        'concernperson',
        'packagename',
        'packagecode',
        'subpackagecode',
        'customercode',
        'customerlanguage',
       'messageID'
    ];

    // public function decryptname()
    // {
    //     return decrypt($this->attributes['name']);
    // }
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = Crypt::encryptString($value);
    // }

    // public function setphoneAttribute($value)
    // {
    //     $this->attributes['phone'] = Crypt::encryptString($value);
    // }

    // public function setemailAttribute($value)
    // {
    //     $this->attributes['email'] = Crypt::encryptString($value);
    // }

    // public function getNameAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getphoneAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getemailAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }
}
