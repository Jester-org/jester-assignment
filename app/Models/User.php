<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // 👈 Add this line

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // 👈 Add HasRoles trait here

    protected $fillable = ['name', 'email', 'password', 'role', 'is_admin'];
    protected $hidden = ['password', 'remember_token'];
}
