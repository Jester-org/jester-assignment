<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        if ($request->expectsJson()) {
            return response()->json($roles);
        }
        return view('role.index', compact('roles'));
    }
    public function create()
    {
        $permissions = Permission::all();
        $users = User::all();
        return view('role.create', compact('permissions', 'users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ]);
        $role = Role::create($request->only('name', 'description'));
        if ($request->permissions) {
            $role->permissions()->sync($request->permissions);
        }
        if ($request->users) {
            $role->users()->sync($request->users);
        }
        if ($request->expectsJson()) {
            return response()->json($role, 201);
        }
        return redirect()->route('roles.index');
    }
    public function show(Request $request, Role $role)
    {
        $role->load('permissions', 'users');
        if ($request->expectsJson()) {
            return response()->json($role);
        }
        return view('role.show', compact('role'));
    }
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $users = User::all();
        return view('role.edit', compact('role', 'permissions', 'users'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ]);
        $role->update($request->only('name', 'description'));
        $role->permissions()->sync($request->permissions ?? []);
        $role->users()->sync($request->users ?? []);
        if ($request->expectsJson()) {
            return response()->json($role);
        }
        return redirect()->route('roles.index');
    }
    public function destroy(Request $request, Role $role)
    {
        $role->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('roles.index');
    }
}

