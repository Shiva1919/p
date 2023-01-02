<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class Serialno extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'ocfno', 'transaction_datetime', 'serialno_issue_date', 'serialno_validity', 'serialno_parameters', 'serialno' ];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Serial Number';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Serial Number";
    }

    public $table="serialno";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'ocfno', 
        'transaction_datetime',
        'serialno_issue_date', 
        'serialno_validity', 
        'serialno_parameters', 
        'serialno',
        'otp_flag'
    ];
}
