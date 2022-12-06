<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Crypt;
class Company extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'customercode', 'companyname', 'panno', 'gstno', 'InstallationType', 'InstallationDesc'];
    
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
        'InstallationType',
        'InstallationDesc'
    ];

   
}
