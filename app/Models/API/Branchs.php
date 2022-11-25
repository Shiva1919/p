<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Branchs extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'customercode', 'branchname', 'branchaddress1', 'branchaddress2', 'branchstate', 'branchdistrict', 'branchtaluka', 'branchcity'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Branch';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Branch";
    }

    public $table="acme_branch";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'customercode', 
        'branchname',
        'branchaddress1', 
        'branchaddress2', 
        'branchstate', 
        'branchdistrict', 
        'branchtaluka', 
        'branchcity'
    ];
}
