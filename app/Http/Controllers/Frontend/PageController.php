<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.about', [
            'stats' => [
                'students' => Student::where('is_active', true)->count(),
                'teachers' => Teacher::where('is_active', true)->count(),
                'classes' => SchoolClass::count(),
            ],
        ]);
    }

    public function principal()
    {
        return view('frontend.principal', [
            'principal' => [
                'name' => Setting::get('principal_name', 'Dr. Sarah Mitchell'),
                'designation' => Setting::get('principal_designation', 'Principal'),
                'message' => Setting::get('principal_message', Setting::get('about_description', 'Welcome to My School.')),
                'photo' => Setting::get('principal_photo'),
                'email' => Setting::get('email'),
            ],
        ]);
    }

    public function academics()
    {
        return view('frontend.academics', [
            'classes' => SchoolClass::ordered()->withCount('students')->with('sections')->get(),
            'subjects' => Subject::with('teacher')->orderBy('name')->get(),
            'sessions' => AcademicSession::orderByDesc('name')->get(),
            'departments' => Teacher::query()->whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
        ]);
    }

    public function classes()
    {
        return view('frontend.classes', [
            'classes' => SchoolClass::ordered()->with(['sections', 'subjects', 'students'])->withCount('students')->get(),
        ]);
    }

    public function subjects()
    {
        return view('frontend.subjects', [
            'subjects' => Subject::with(['teacher', 'schoolClass'])->orderBy('name')->get(),
            'classes' => SchoolClass::ordered()->get(),
        ]);
    }

    public function facilities()
    {
        return view('frontend.facilities');
    }

    public function students()
    {
        // Only non-sensitive directory information is exposed publicly.
        $students = Student::where('is_active', true)
            ->with(['schoolClass', 'section'])
            ->orderBy('name')
            ->paginate(24);

        return view('frontend.students', compact('students'));
    }

    public function admissionsInfo()
    {
        return view('frontend.admission-info', [
            'classes' => SchoolClass::ordered()->pluck('name'),
        ]);
    }
}
