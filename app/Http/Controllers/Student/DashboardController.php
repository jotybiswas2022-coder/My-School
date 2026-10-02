<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function student()
    {
        return Auth::guard('student')->user()->load(['schoolClass', 'section']);
    }

    public function index()
    {
        $student = $this->student();

        return view('frontend.student.dashboard', [
            'student' => $student,
            'summary' => $student->attendanceSummary(),
            'results' => Result::with(['subject', 'exam'])
                ->where('student_id', $student->id)
                ->where('is_published', true)
                ->latest()
                ->take(6)
                ->get(),
            'notices' => Notice::published()->take(5)->get(),
            'events' => Event::published()->upcoming()->take(4)->get(),
            'recentAttendance' => Attendance::where('student_id', $student->id)->latest('date')->take(6)->get(),
        ]);
    }

    public function profile()
    {
        return view('frontend.student.profile', ['student' => $this->student()]);
    }

    public function attendance()
    {
        $student = $this->student();

        return view('frontend.student.attendance', [
            'student' => $student,
            'summary' => $student->attendanceSummary(),
            'attendances' => Attendance::where('student_id', $student->id)
                ->orderByDesc('date')
                ->paginate(20),
        ]);
    }

    public function results()
    {
        $student = $this->student();

        $results = Result::with(['subject', 'exam'])
            ->where('student_id', $student->id)
            ->where('is_published', true)
            ->get()
            ->groupBy('exam_id');

        return view('frontend.student.results', compact('student', 'results'));
    }

    public function notices()
    {
        return view('frontend.student.notices', [
            'student' => $this->student(),
            'notices' => Notice::published()->paginate(10),
        ]);
    }

    public function academics()
    {
        $student = $this->student();

        return view('frontend.student.academics', [
            'student' => $student,
            'subjects' => $student->schoolClass
                ? $student->schoolClass->subjects()->with('teacher')->get()
                : collect(),
            'events' => Event::published()->upcoming()->take(5)->get(),
        ]);
    }
}
