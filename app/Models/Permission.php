<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Database\Factories\PermissionFactory;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'description', 'guard_name'];

    protected static function newFactory()
    {
        return PermissionFactory::new();
    }
}
