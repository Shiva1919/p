<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class ChangeRequestAction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use  HasFactory, LogsActivity;

    protected static $logAttributes = [ 'requestontable', 'usercode', 'whattochange', 'actionvalue', 'description', 'actiondatedtime'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'CustomerChangeRequest';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} CustomerChangeRequest";
    }
    use HasFactory;
    public $table="changerequestaction";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'requestontable',
        'usercode',
        'whattochange',
        'actionvalue',
        'description',
        'actiondatedtime'
    ];
}
