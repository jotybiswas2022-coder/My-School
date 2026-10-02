<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::with('academicSession')
            ->withCount(['results', 'examSubjects'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('backend.exams.form', [
            'exam' => new Exam(['start_date' => now()]),
            'sessions' => AcademicSession::orderByDesc('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Exam::create($this->validated($request));

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        return view('backend.exams.show', [
            'exam' => $exam->load(['academicSession', 'examSubjects.subject', 'examSubjects.schoolClass']),
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => SchoolClass::ordered()->get(),
            'sessions' => AcademicSession::orderByDesc('name')->get(),
        ]);
    }

    public function edit(Exam $exam)
    {
        return view('backend.exams.form', [
            'exam' => $exam,
            'sessions' => AcademicSession::orderByDesc('name')->get(),
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        $exam->update($this->validated($request));

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function togglePublish(Exam $exam)
    {
        $exam->update(['is_published' => ! $exam->is_published]);
        $exam->results()->update(['is_published' => $exam->is_published]);

        return back()->with('success', $exam->is_published ? 'Exam results published.' : 'Exam results unpublished.');
    }

    public function storeSubject(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'exam_date' => ['nullable', 'date'],
            'full_marks' => ['required', 'integer', 'min:1', 'max:500'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lt:full_marks'],
        ]);

        ExamSubject::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'subject_id' => $data['subject_id'],
                'class_id' => $data['class_id'] ?? null,
            ],
            $data
        );

        return back()->with('success', 'Exam subject saved successfully.');
    }

    public function destroySubject(ExamSubject $examSubject)
    {
        $examSubject->delete();

        return back()->with('success', 'Exam subject removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'exam_type' => ['nullable', 'string', 'max:60'],
            'academic_session_id' => ['nullable', 'exists:academic_sessions,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_published' => ['nullable', 'boolean'],
        ]) + ['is_published' => $request->boolean('is_published')];
    }
}
