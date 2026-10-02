<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $results = Result::with(['student.schoolClass', 'subject', 'exam'])
            ->when($request->filled('exam_id'), fn ($q) => $q->where('exam_id', $request->integer('exam_id')))
            ->when($request->filled('class_id'), fn ($q) => $q->whereHas('student', fn ($s) => $s->where('class_id', $request->integer('class_id'))))
            ->when($request->filled('q'), fn ($q) => $q->whereHas('student', fn ($s) => $s->where('name', 'like', '%' . $request->string('q') . '%')
                ->orWhere('student_id', 'like', '%' . $request->string('q') . '%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('backend.results.index', [
            'results' => $results,
            'exams' => Exam::orderByDesc('id')->get(),
            'classes' => SchoolClass::ordered()->get(),
        ]);
    }

    public function create(Request $request)
    {
        $examId = $request->integer('exam_id') ?: Exam::orderByDesc('id')->value('id');
        $classId = $request->integer('class_id');
        $subjectId = $request->integer('subject_id');

        $students = collect();
        $existing = collect();

        if ($classId && $subjectId) {
            $students = Student::where('class_id', $classId)->where('is_active', true)->orderBy('name')->get();
            $existing = Result::where('exam_id', $examId)
                ->where('subject_id', $subjectId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()->keyBy('student_id');
        }

        return view('backend.results.form', [
            'exams' => Exam::orderByDesc('id')->get(),
            'classes' => SchoolClass::ordered()->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'examId' => $examId,
            'classId' => $classId,
            'subjectId' => $subjectId,
            'students' => $students,
            'existing' => $existing,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'full_marks' => ['required', 'integer', 'min:1', 'max:500'],
            'marks' => ['required', 'array'],
            'marks.*' => ['nullable', 'numeric', 'min:0', 'lte:full_marks'],
        ]);

        $exam = Exam::find($data['exam_id']);

        DB::transaction(function () use ($data, $exam) {
            foreach ($data['marks'] as $studentId => $marks) {
                if ($marks === null || $marks === '') {
                    continue;
                }

                $grade = Result::gradeFor((float) $marks, (int) $data['full_marks']);

                Result::updateOrCreate(
                    [
                        'exam_id' => $data['exam_id'],
                        'student_id' => $studentId,
                        'subject_id' => $data['subject_id'],
                    ],
                    [
                        'marks' => $marks,
                        'full_marks' => $data['full_marks'],
                        'grade' => $grade['grade'],
                        'gpa' => $grade['gpa'],
                        'is_published' => $exam->is_published,
                    ]
                );
            }
        });

        return redirect()->route('admin.results.create', [
            'exam_id' => $data['exam_id'],
            'subject_id' => $data['subject_id'],
        ])->with('success', 'Marks saved successfully.');
    }

    public function edit(Result $result)
    {
        return view('backend.results.edit', [
            'result' => $result->load(['student', 'subject', 'exam']),
        ]);
    }

    public function update(Request $request, Result $result)
    {
        $data = $request->validate([
            'marks' => ['required', 'numeric', 'min:0', 'max:' . $result->full_marks],
            'full_marks' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $grade = Result::gradeFor((float) $data['marks'], (int) $data['full_marks']);

        $result->update([
            'marks' => $data['marks'],
            'full_marks' => $data['full_marks'],
            'grade' => $grade['grade'],
            'gpa' => $grade['gpa'],
        ]);

        return redirect()->route('admin.results.index')->with('success', 'Result updated successfully.');
    }

    public function destroy(Result $result)
    {
        $result->delete();

        return back()->with('success', 'Result deleted successfully.');
    }

    public function publish(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'publish' => ['required', 'boolean'],
        ]);

        Result::where('exam_id', $data['exam_id'])->update(['is_published' => $request->boolean('publish')]);

        return back()->with('success', $request->boolean('publish') ? 'Results published.' : 'Results unpublished.');
    }
}
