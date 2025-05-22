<?php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Database\Factories\RoleFactory;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'description', 'guard_name'];

    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
