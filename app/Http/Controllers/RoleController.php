<?php
namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        if ($request->expectsJson()) {
            return response()->json($roles);
        }
        return view('role.index', compact('roles'));
    }
    public function create()
    {
        return view('role.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
        ]);
        $role = Role::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($role, 201);
        }
        return redirect()->route('roles.index');
    }
    public function show(Request $request, Role $role)
    {
        if ($request->expectsJson()) {
            return response()->json($role);
        }
        return view('role.show', compact('role'));
    }
    public function edit(Role $role)
    {
        return view('role.edit', compact('role'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);
        $role->update($request->all());
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

