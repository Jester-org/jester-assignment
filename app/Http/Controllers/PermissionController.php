<?php
namespace App\Http\Controllers;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::with('roles')->get();
        if ($request->expectsJson()) {
            return response()->json($permissions);
        }
        return view('permission.index', compact('permissions'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('permission.create', compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);
        $permission = Permission::create($request->only('name', 'description'));
        if ($request->roles) {
            $permission->roles()->sync($request->roles);
        }
        if ($request->expectsJson()) {
            return response()->json($permission, 201);
        }
        return redirect()->route('permissions.index');
    }
    public function show(Request $request, Permission $permission)
    {
        $permission->load('roles');
        if ($request->expectsJson()) {
            return response()->json($permission);
        }
        return view('permission.show', compact('permission'));
    }
    public function edit(Permission $permission)
    {
        $roles = Role::all();
        return view('permission.edit', compact('permission', 'roles'));
    }
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);
        $permission->update($request->only('name', 'description'));
        $permission->roles()->sync($request->roles ?? []);
        if ($request->expectsJson()) {
            return response()->json($permission);
        }
        return redirect()->route('permissions.index');
    }
    public function destroy(Request $request, Permission $permission)
    {
        $permission->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('permissions.index');
    }
}

