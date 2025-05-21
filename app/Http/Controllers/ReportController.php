<?php
namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Http\Request;
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::all();
        if ($request->expectsJson()) {
            return response()->json($reports);
        }
        return view('report.index', compact('reports'));
    }
    public function create()
    {
        return view('report.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sales,inventory,payment,user_activity',
            'description' => 'nullable|string',
            'generated_at' => 'nullable|date',
        ]);
        $report = Report::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($report, 201);
        }
        return redirect()->route('reports.index');
    }
    public function show(Request $request, Report $report)
    {
        if ($request->expectsJson()) {
            return response()->json($report);
        }
        return view('report.show', compact('report'));
    }
    public function edit(Report $report)
    {
        return view('report.edit', compact('report'));
    }
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sales,inventory,payment,user_activity',
            'description' => 'nullable|string',
            'generated_at' => 'nullable|date',
        ]);
        $report->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($report);
        }
        return redirect()->route('reports.index');
    }
    public function destroy(Request $request, Report $report)
    {
        $report->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('reports.index');
    }
}

