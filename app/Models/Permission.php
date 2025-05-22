<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'description', 'guard_name'];

    protected static function newFactory()
    {
        return PermissionFactory::new();
    }

    // Roles that have this permission
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    // Users that have this permission
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user');
    }
}