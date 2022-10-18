<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'uuid', 'name', 'last_name', 'email', 'email_verified_at', 'phone',  'password', 'password_changed_at', 'active', 'confirmation_code', 'confirmed', 'role_id', 'permission_id', 'timezone', 'last_login_at', 'last_login_ip', 'to_be_logged_out', 'status', 'created_by', 'updated_by', 'is_term_accept',
    ];
}
