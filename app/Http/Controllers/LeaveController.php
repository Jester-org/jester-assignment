<?php
namespace App\Http\Controllers;
use App\Models\Leave;
use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Http\Request;
class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = Leave::with(['user', 'leaveType'])->get();
        if ($request->expectsJson()) {
            return response()->json($leaves);
        }
        return view('leave.index', compact('leaves'));
    }
    public function create()
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        return view('leave.create', compact('users', 'leaveTypes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
        ]);
        $leave = Leave::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($leave, 201);
        }
        return redirect()->route('leaves.index');
    }
    public function show(Request $request, Leave $leave)
    {
        $leave->load(['user', 'leaveType']);
        if ($request->expectsJson()) {
            return response()->json($leave);
        }
        return view('leave.show', compact('leave'));
    }
    public function edit(Leave $leave)
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        return view('leave.edit', compact('leave', 'users', 'leaveTypes'));
    }
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
        ]);
        $leave->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($leave);
        }
        return redirect()->route('leaves.index');
    }
    public function destroy(Request $request, Leave $leave)
    {
        $leave->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('leaves.index');
    }
}

