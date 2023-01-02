<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements JWTSubject, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;
    use \OwenIt\Auditing\Auditable;
    protected static $logAttributes = ['name', 'email', 'password'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected static $logName = 'Users';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} User";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'name',
        'role_id',
        'email',
        'phone',
        'permission_id',
        'gender',
        'password',
        'rowpassword',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
