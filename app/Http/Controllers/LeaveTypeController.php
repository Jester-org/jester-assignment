<?php
namespace App\Http\Controllers;
use App\Models\LeaveType;
use Illuminate\Http\Request;
class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::all();
        if ($request->expectsJson()) {
            return response()->json($leaveTypes);
        }
        return view('leave_type.index', compact('leaveTypes'));
    }
    public function create()
    {
        return view('leave_type.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types',
            'description' => 'nullable|string',
        ]);
        $leaveType = LeaveType::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($leaveType, 201);
        }
        return redirect()->route('leave_types.index');
    }
    public function show(Request $request, LeaveType $leaveType)
    {
        if ($request->expectsJson()) {
            return response()->json($leaveType);
        }
        return view('leave_type.show', compact('leaveType'));
    }
    public function edit(LeaveType $leaveType)
    {
        return view('leave_type.edit', compact('leaveType'));
    }
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
        ]);
        $leaveType->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($leaveType);
        }
        return redirect()->route('leave_types.index');
    }
    public function destroy(Request $request, LeaveType $leaveType)
    {
        $leaveType->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('leave_types.index');
    }
}

