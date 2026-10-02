<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('designation', 'like', $term)
                        ->orWhere('qualification', 'like', $term);
                });
            })
            ->when($request->filled('department'), fn ($q) => $q->where('department', $request->string('department')))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.teachers', [
            'teachers' => $teachers,
            'departments' => Teacher::whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
        ]);
    }

    public function show(Teacher $teacher)
    {
        abort_unless($teacher->is_active, 404);

        return view('frontend.teacher-show', [
            'teacher' => $teacher->load('subjects.schoolClass'),
        ]);
    }
}
