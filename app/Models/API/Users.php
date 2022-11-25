<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Users extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['uuid', 'name', 'last_name', 'email', 'email_verified_at', 'phone',  'password', 'password_changed_at', 'active', 'confirmation_code', 'confirmed', 'role_id', 'permission_id', 'timezone', 'last_login_at', 'last_login_ip', 'to_be_logged_out', 'status', 'created_by', 'updated_by', 'is_term_accept'];


    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'User';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} User";
    }

    protected $table = 'users';
    protected $fillable = [
        'uuid', 'name', 'last_name', 'email', 'email_verified_at', 'phone',  'password', 'password_changed_at', 'active', 'confirmation_code', 'confirmed', 'role_id', 'permission_id', 'timezone', 'last_login_at', 'last_login_ip', 'to_be_logged_out', 'status', 'created_by', 'updated_by', 'is_term_accept',
    ];
}
