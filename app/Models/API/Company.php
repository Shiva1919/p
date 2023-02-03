<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Crypt;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'customercode', 'companyname', 'panno', 'gstno', 'gsttype', 'InstallationType', 'InstallationDesc'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Company';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Company";
    }

    public $table="company_master";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customercode',
        'companyname',
        'panno',
        'gstno',
        'gsttype',
        'InstallationType',
        'InstallationDesc'
    ];

    // public function setcompanynameAttribute($value)
    // {
    //     $this->attributes['companyname'] = Crypt::encryptString($value);
    // }

    // public function setpannoAttribute($value)
    // {
    //     $this->attributes['panno'] = Crypt::encryptString($value);
    // }

    // public function setgstnoAttribute($value)
    // {
    //     $this->attributes['gstno'] = Crypt::encryptString($value);
    // }

    // public function getcompanynameAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getpannoAttribute($value)
    // {
    //     try{
    //         return Crypt::decryptString($value);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return $value;
    //     }
    // }

    // public function getgstnoAttribute($value)
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
