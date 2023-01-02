<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class Modules extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'productcode', 'ModuleName', 'description', 'price', 'unit', 'moduletype','producttype','active','moduletypeid'];
    
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Modules';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Modules";
    }

    public $table="acme_module";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'productcode',
        'ModuleName',
        'description', 
        'price',
        'unit',
        'moduletype',
        'producttype',
        'active',
        'moduletypeid'
    ];
}
