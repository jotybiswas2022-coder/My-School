<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        return view('frontend.results', [
            'exams' => Exam::where('is_published', true)->orderByDesc('id')->get(),
            'sessions' => AcademicSession::orderByDesc('name')->get(),
            'searched' => false,
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'string', 'max:50'],
            'exam_id' => ['required', 'exists:exams,id'],
            'academic_session_id' => ['nullable', 'exists:academic_sessions,id'],
        ]);

        $student = Student::with(['schoolClass', 'section'])
            ->where('student_id', $data['student_id'])
            ->first();

        $exam = Exam::with('academicSession')->find($data['exam_id']);

        $results = collect();
        if ($student) {
            $results = Result::with('subject')
                ->where('student_id', $student->id)
                ->where('exam_id', $exam->id)
                ->where('is_published', true)
                ->get();
        }

        $summary = null;
        if ($results->isNotEmpty()) {
            $totalGpa = $results->avg('gpa');
            $totalMarks = $results->sum('marks');
            $totalFull = $results->sum('full_marks');
            $summary = [
                'gpa' => round($totalGpa, 2),
                'obtained' => $totalMarks,
                'full' => $totalFull,
                'percentage' => $totalFull > 0 ? round(($totalMarks / $totalFull) * 100, 2) : 0,
                'grade' => Result::gradeFor($totalFull > 0 ? ($totalMarks / $totalFull) * 100 : 0, 100)['grade'],
                'subjects' => $results->count(),
            ];
        }

        return view('frontend.results', [
            'exams' => Exam::where('is_published', true)->orderByDesc('id')->get(),
            'sessions' => AcademicSession::orderByDesc('name')->get(),
            'searched' => true,
            'student' => $student,
            'exam' => $exam,
            'results' => $results,
            'summary' => $summary,
        ]);
    }
}
