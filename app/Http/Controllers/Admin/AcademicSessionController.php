<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    public function index()
    {
        return view('backend.sessions.index', [
            'sessions' => AcademicSession::withCount('exams')->orderByDesc('name')->get(),
            'session' => new AcademicSession(['is_current' => false]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:academic_sessions,name'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_current')) {
            AcademicSession::query()->update(['is_current' => false]);
        }

        AcademicSession::create($data + ['is_current' => $request->boolean('is_current')]);

        return back()->with('success', 'Academic session created successfully.');
    }

    public function update(Request $request, AcademicSession $session)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:academic_sessions,name,' . $session->id],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_current')) {
            AcademicSession::query()->where('id', '!=', $session->id)->update(['is_current' => false]);
        }

        $session->update($data + ['is_current' => $request->boolean('is_current')]);

        return back()->with('success', 'Academic session updated successfully.');
    }

    public function destroy(AcademicSession $session)
    {
        $session->delete();

        return back()->with('success', 'Academic session deleted successfully.');
    }
}
