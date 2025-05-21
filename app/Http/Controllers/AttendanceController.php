<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with('user')->get();
        if ($request->expectsJson()) {
            return response()->json($attendances);
        }
        return view('attendance.index', compact('attendances'));
    }
    public function create()
    {
        $users = User::all();
        return view('attendance.create', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'check_in' => 'required|date',
            'check_out' => 'nullable|date|after:check_in',
            'status' => 'required|in:present,absent,late',
        ]);
        $attendance = Attendance::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($attendance, 201);
        }
        return redirect()->route('attendances.index');
    }
    public function show(Request $request, Attendance $attendance)
    {
        $attendance->load('user');
        if ($request->expectsJson()) {
            return response()->json($attendance);
        }
        return view('attendance.show', compact('attendance'));
    }
    public function edit(Attendance $attendance)
    {
        $users = User::all();
        return view('attendance.edit', compact('attendance', 'users'));
    }
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'check_in' => 'required|date',
            'check_out' => 'nullable|date|after:check_in',
            'status' => 'required|in:present,absent,late',
        ]);
        $attendance->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($attendance);
        }
        return redirect()->route('attendances.index');
    }
    public function destroy(Request $request, Attendance $attendance)
    {
        $attendance->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('attendances.index');
    }
}

