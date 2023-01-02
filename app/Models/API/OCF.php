<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use OwenIt\Auditing\Contracts\Auditable;

class OCF extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
  use HasFactory, LogsActivity;

  protected static $logAttributes = [ 'customercode', 'companycode', 'Series', 'DocNo', 'ocf_date', 'AmountTotal'];

  protected static $recordEvents = ['created', 'updated', 'deleted'];

  protected static $logName = 'OCF';

  public function getDescriptionForEvent(string $eventName): string
  {
      return "You have {$eventName} OCF";
  }

    public $table="ocf_master";
    // public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customercode',
        'companycode',
        'Series',
        'DocNo',
        'ocf_date',
        'AmountTotal',
      ];
}
