<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index()
    {
        $established = (int) (Setting::get('established_year') ?? 1998);

        $stats = [
            'students' => Student::where('is_active', true)->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'classes' => SchoolClass::count(),
            'years' => max(1, now()->year - $established),
        ];

        $heroSlides = [];
        if ($heroImage = Setting::get('hero_image')) {
            $heroSlides[] = asset('storage/' . $heroImage);
        }
        foreach (GalleryImage::latest()->take(8)->pluck('image') as $image) {
            $heroSlides[] = asset('storage/' . $image);
        }
        $heroSlides = array_values(array_unique(array_filter($heroSlides)));

        return view('frontend.home', [
            'notices' => Notice::published()->take(4)->get(),
            'ticker' => Notice::published()->take(5)->get(),
            'events' => Event::published()->upcoming()->take(3)->get(),
            'latestNews' => News::published()->take(3)->get(),
            'featuredTeachers' => Teacher::where('is_featured', true)->where('is_active', true)->take(4)->get(),
            'programs' => SchoolClass::ordered()->take(6)->withCount('students')->get(),
            'albums' => GalleryAlbum::with('images')->latest()->take(5)->get(),
            'stats' => $stats,
            'heroSlides' => $heroSlides,
            'principal' => [
                'name' => Setting::get('principal_name', 'Dr. Sarah Mitchell'),
                'designation' => Setting::get('principal_designation', 'Principal'),
                'message' => Setting::get('principal_message', 'Welcome to My School.'),
                'photo' => Setting::get('principal_photo'),
            ],
            'subjectsCount' => Subject::count(),
            'currentSession' => AcademicSession::current(),
        ]);
    }
}
