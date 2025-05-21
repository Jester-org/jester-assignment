<?php
namespace App\Http\Controllers;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $auditLogs = AuditLog::with('user')->get();
        if ($request->expectsJson()) {
            return response()->json($auditLogs);
        }
        return view('audit_log.index', compact('auditLogs'));
    }
    public function create()
    {
        $users = User::all();
        return view('audit_log.create', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'description' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);
        $auditLog = AuditLog::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($auditLog, 201);
        }
        return redirect()->route('audit_logs.index');
    }
    public function show(Request $request, AuditLog $auditLog)
    {
        $auditLog->load('user');
        if ($request->expectsJson()) {
            return response()->json($auditLog);
        }
        return view('audit_log.show', compact('auditLog'));
    }
    public function edit(AuditLog $auditLog)
    {
        $users = User::all();
        return view('audit_log.edit', compact('auditLog', 'users'));
    }
    public function update(Request $request, AuditLog $auditLog)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'description' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);
        $auditLog->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($auditLog);
        }
        return redirect()->route('audit_logs.index');
    }
    public function destroy(Request $request, AuditLog $auditLog)
    {
        $auditLog->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('audit_logs.index');
    }
}

