<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class Packages extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, LogsActivity;

    protected static $logAttributes = [ 'packagename','description','active'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Packages';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} Packages";
    }

    public $table="acme_package";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'packagename',
        'description',
        'active'
    ];
}
