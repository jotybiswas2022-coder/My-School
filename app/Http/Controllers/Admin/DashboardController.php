<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Attendance;
use App\Models\Contact;
use App\Models\Event;
use App\Models\News;
use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard', [
            'stats' => [
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'classes' => SchoolClass::count(),
                'subjects' => Subject::count(),
                'notices' => Notice::count(),
                'events' => Event::count(),
                'news' => News::count(),
                'pendingAdmissions' => Admission::where('status', 'pending')->count(),
                'messages' => Contact::where('is_read', false)->count(),
            ],
            'recentAdmissions' => Admission::latest()->take(5)->get(),
            'recentNotices' => Notice::latest()->take(5)->get(),
            'upcomingEvents' => Event::published()->upcoming()->take(5)->get(),
            'recentMessages' => Contact::latest()->take(5)->get(),
            'todayAttendance' => Attendance::whereDate('date', now()->toDateString())
                ->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }
}
