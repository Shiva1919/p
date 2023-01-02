<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class CompanyChangeRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use  HasFactory, LogsActivity;

    protected static $logAttributes = [ 'customercode', 'companycode', 'whattochange', 'oldvalue', 'newvalue', 'requestdatetime', 'status'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'CompanyChangeRequest';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} CompanyChangeRequest";
    }
    use HasFactory;
    public $table="companychangerequest";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customercode',
        'companycode',
        'whattochange',
        'oldvalue',
        'newvalue',
        'requestdatetime',
        'status'
    ];
}
