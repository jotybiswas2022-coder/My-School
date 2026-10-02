<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Admission;
use App\Models\Attendance;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\Notice;
use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private \Faker\Generator $faker;

    public function run(): void
    {
        $this->faker = Faker::create();

        $this->seedSettings();
        $this->seedUsers();
        $this->seedSessions();

        [$classes, $teachers] = [$this->seedClasses(), $this->seedTeachers()];
        $subjects = $this->seedSubjects($classes, $teachers);
        $students = $this->seedStudents($classes);
        $this->seedAttendance($students);
        $exams = $this->seedExams();
        $this->seedResults($exams, $subjects, $students);
        $this->seedContent();
        $this->seedAdmissionsAndMessages();
    }

    /* ------------------------------------------------------------------ */

    private function seedSettings(): void
    {
        Setting::put('school_name', 'My School');
        Setting::put('tagline', 'Empowering Students. Inspiring Futures.');
        Setting::put('email', 'info@myschool.edu');
        Setting::put('phone', '+1 (555) 123-4567');
        Setting::put('address', '123 Education Avenue, Springfield, ST 12345');
        Setting::put('office_hours', 'Monday - Friday, 8:00 AM - 4:00 PM');
        Setting::put('established_year', '1998');
        Setting::put('principal_name', 'Dr. Sarah Mitchell');
        Setting::put('principal_designation', 'Principal');
        Setting::put('principal_message', "Welcome to My School. For more than two decades we have been a place where curiosity is celebrated and character is built.\n\nOur dedicated teachers, modern facilities and vibrant community create an environment where every student can discover their strengths. We believe education is more than examinations - it is about becoming thoughtful, resilient and compassionate people.\n\nI warmly invite you to visit our campus, meet our teachers and see for yourself what makes our school special.");
        Setting::put('about_description', 'My School is a modern learning community dedicated to academic excellence, creativity and character development. Since 1998 we have helped thousands of students discover their potential in a caring and inclusive environment.');
        Setting::put('mission', 'To provide an inspiring, future-ready education that develops confident, responsible and compassionate global citizens.');
        Setting::put('vision', 'To be a leading school recognised for academic innovation, inclusion and character formation.');
        Setting::put('footer_text', '© ' . date('Y') . ' My School. All rights reserved.');
        Setting::put('facebook', 'https://facebook.com/myschool');
        Setting::put('twitter', 'https://twitter.com/myschool');
        Setting::put('instagram', 'https://instagram.com/myschool');
        Setting::put('youtube', 'https://youtube.com/@myschool');
        Setting::put('admission_open', '1');
    }

    private function seedUsers(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@myschool.edu'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        Setting::put('admin_user_id', (string) $admin->id);
    }

    private function seedSessions(): void
    {
        AcademicSession::updateOrCreate(['name' => '2024-2025'], [
            'start_date' => '2024-04-01',
            'end_date' => '2025-03-31',
            'is_current' => false,
        ]);

        AcademicSession::updateOrCreate(['name' => '2025-2026'], [
            'start_date' => '2025-04-01',
            'end_date' => '2026-03-31',
            'is_current' => true,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, SchoolClass>
     */
    private function seedClasses(): \Illuminate\Support\Collection
    {
        $names = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];

        foreach ($names as $index => $name) {
            $class = SchoolClass::updateOrCreate(
                ['name' => $name],
                [
                    'code' => 'G' . ($index + 1),
                    'description' => 'A balanced curriculum focused on strong foundations and holistic growth for ' . $name . ' students.',
                    'order' => $index,
                ]
            );

            foreach (['A', 'B'] as $sectionName) {
                Section::updateOrCreate(
                    ['class_id' => $class->id, 'name' => $sectionName],
                    ['capacity' => 40]
                );
            }
        }

        return SchoolClass::ordered()->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Teacher>
     */
    private function seedTeachers(): \Illuminate\Support\Collection
    {
        $teachers = [
            ['Dr. Sarah Mitchell', 'Principal', 'Administration', 'Ph.D. in Education', '24 years'],
            ['Mr. James Carter', 'Vice Principal', 'Administration', 'M.Ed, B.A', '18 years'],
            ['Ms. Emily Nguyen', 'Senior Teacher', 'Science', 'M.Sc Physics, B.Ed', '12 years'],
            ['Mr. David Okafor', 'Senior Teacher', 'Mathematics', 'M.Sc Mathematics', '14 years'],
            ['Mrs. Aisha Rahman', 'Teacher', 'English', 'M.A English, B.Ed', '9 years'],
            ['Mr. Lucas Silva', 'Teacher', 'Science', 'M.Sc Chemistry', '7 years'],
            ['Ms. Hannah Lee', 'Teacher', 'Computer Science', 'B.Sc Computer Science', '6 years'],
            ['Mr. Mohammed Ali', 'Teacher', 'Mathematics', 'B.Sc Mathematics, B.Ed', '10 years'],
            ['Ms. Grace Thompson', 'Teacher', 'Humanities', 'M.A History', '8 years'],
            ['Mr. Ryan Patel', 'Teacher', 'Physical Education', 'B.P.Ed', '11 years'],
            ['Ms. Sofia Rossi', 'Teacher', 'Languages', 'M.A French', '5 years'],
            ['Mr. Daniel Kim', 'Teacher', 'Arts', 'B.F.A', '6 years'],
            ['Mrs. Fatima Noor', 'Teacher', 'English', 'M.Ed, B.A', '13 years'],
            ['Mr. Ethan Brooks', 'Teacher', 'Humanities', 'M.A Geography', '9 years'],
        ];

        $photo = $this->sampleImage('teacher-portrait', 'Academic Staff', ['#2563EB', '#1E40AF']);

        foreach ($teachers as $index => [$name, $designation, $department, $qualification, $experience]) {
            Teacher::updateOrCreate(
                ['email' => strtolower(str_replace([' ', '.', "'"], ['', '', ''], $name)) . '@myschool.edu'],
                [
                    'name' => $name,
                    'designation' => $designation,
                    'department' => $department,
                    'qualification' => $qualification,
                    'experience' => $experience,
                    'phone' => '+1 (555) ' . str_pad((string) (200 + $index), 3, '0', STR_PAD_LEFT) . '-' . $this->faker->numerify('####'),
                    'bio' => $name . ' has been part of the My School community for ' . $experience . '. ' . $this->faker->paragraph(3),
                    'photo' => $photo,
                    'join_date' => now()->subYears((int) filter_var($experience, FILTER_SANITIZE_NUMBER_INT))->startOfYear(),
                    'is_featured' => $index < 4,
                    'is_active' => true,
                ]
            );
        }

        return Teacher::orderBy('id')->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Subject>
     */
    private function seedSubjects(\Illuminate\Support\Collection $classes, \Illuminate\Support\Collection $teachers): \Illuminate\Support\Collection
    {
        $definitions = [
            ['English Language', 'ENG', 'English'],
            ['Mathematics', 'MATH', 'Mathematics'],
            ['General Science', 'SCI', 'Science'],
            ['Social Studies', 'SOC', 'Humanities'],
            ['Computer Science', 'COMP', 'Computer Science'],
            ['Physical Education', 'PE', 'Physical Education'],
            ['Fine Arts', 'ART', 'Arts'],
            ['Second Language', 'LANG', 'Languages'],
        ];

        foreach ($definitions as [$name, $code, $department]) {
            $teacher = $teachers->firstWhere('department', $department) ?? $teachers->first();

            Subject::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'description' => 'A comprehensive ' . $name . ' programme developing core knowledge, critical thinking and practical skills.',
                    'teacher_id' => $teacher?->id,
                ]
            );
        }

        $subjects = Subject::orderBy('id')->get();

        foreach ($classes as $class) {
            $class->subjects()->syncWithoutDetaching($subjects->pluck('id')->all());
        }

        return $subjects;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Student>
     */
    private function seedStudents(\Illuminate\Support\Collection $classes): \Illuminate\Support\Collection
    {
        $photo = $this->sampleImage('student-portrait', 'Student', ['#0EA5E9', '#2563EB']);
        $counter = 1;

        foreach ($classes as $class) {
            $sections = $class->sections;

            for ($i = 0; $i < 6; $i++) {
                $section = $sections[$i % max(1, $sections->count())] ?? null;

                Student::updateOrCreate(
                    ['student_id' => 'STU-2026-' . str_pad((string) $counter, 3, '0', STR_PAD_LEFT)],
                    [
                        'name' => $this->faker->name(),
                        'email' => 'student' . $counter . '@myschool.edu',
                        'password' => Hash::make('password'),
                        'date_of_birth' => now()->subYears(6 + (int) $class->order)->subDays($this->faker->numberBetween(0, 300))->toDateString(),
                        'gender' => $this->faker->randomElement(['male', 'female']),
                        'class_id' => $class->id,
                        'section_id' => $section?->id,
                        'roll_number' => (string) ($i + 1),
                        'guardian_name' => $this->faker->name(),
                        'guardian_phone' => '+1 (555) ' . $this->faker->numerify('###-####'),
                        'address' => $this->faker->streetAddress() . ', Springfield',
                        'photo' => null,
                        'admission_date' => now()->subMonths($this->faker->numberBetween(1, 20))->toDateString(),
                        'is_active' => true,
                    ]
                );

                $counter++;
            }
        }

        return Student::with('schoolClass')->orderBy('id')->get();
    }

    private function seedAttendance(\Illuminate\Support\Collection $students): void
    {
        $dates = collect(range(0, 13))
            ->map(fn ($i) => now()->subDays($i))
            ->reject(fn ($date) => $date->isWeekend())
            ->take(10)
            ->values();

        $rows = [];
        foreach ($students as $student) {
            foreach ($dates as $date) {
                $status = $this->faker->randomElement(['present', 'present', 'present', 'present', 'late', 'absent']);
                $rows[] = [
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'section_id' => $student->section_id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'remark' => $status === 'late' ? 'Arrived late' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            Attendance::insertOrIgnore($chunk);
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, Exam>
     */
    private function seedExams(): \Illuminate\Support\Collection
    {
        $current = AcademicSession::where('is_current', true)->first();
        $previous = AcademicSession::where('is_current', false)->first();

        $definitions = [
            ['First Term Examination', 'Written', $previous, now()->subMonths(8), now()->subMonths(7), true],
            ['Mid Term Examination', 'Written', $current, now()->subMonths(3), now()->subMonths(3)->addDays(10), true],
            ['Final Term Examination', 'Written', $current, now()->addMonth(), now()->addMonth()->addDays(12), false],
        ];

        foreach ($definitions as [$name, $type, $session, $start, $end, $published]) {
            Exam::updateOrCreate(['name' => $name], [
                'exam_type' => $type,
                'academic_session_id' => $session?->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'is_published' => $published,
            ]);
        }

        $exams = Exam::orderBy('id')->get();
        $subjects = Subject::all();

        foreach ($exams as $exam) {
            foreach ($subjects as $index => $subject) {
                ExamSubject::updateOrCreate(
                    ['exam_id' => $exam->id, 'subject_id' => $subject->id, 'class_id' => null],
                    [
                        'exam_date' => $exam->start_date?->copy()->addDays($index)->toDateString(),
                        'full_marks' => 100,
                        'pass_marks' => 33,
                    ]
                );
            }
        }

        return $exams;
    }

    private function seedResults(\Illuminate\Support\Collection $exams, \Illuminate\Support\Collection $subjects, \Illuminate\Support\Collection $students): void
    {
        foreach ($exams as $exam) {
            // Only seed results for published exams plus the draft one, keeping volume reasonable.
            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    $marks = $this->faker->numberBetween(38, 99);
                    $grade = Result::gradeFor($marks, 100);

                    Result::updateOrCreate(
                        ['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $subject->id],
                        [
                            'marks' => $marks,
                            'full_marks' => 100,
                            'grade' => $grade['grade'],
                            'gpa' => $grade['gpa'],
                            'is_published' => $exam->is_published,
                        ]
                    );
                }
            }
        }
    }

    private function seedContent(): void
    {
        $adminId = User::where('is_admin', true)->value('id');

        $noticeTemplates = [
            ['Annual Sports Day Announcement', 'Event', 'Our annual sports day will be held next month. All students are encouraged to participate in at least one event.'],
            ['Mid Term Examination Schedule', 'Exam', 'The mid term examination schedule has now been published. Please collect your admit card from the class teacher.'],
            ['Parent-Teacher Meeting', 'Academic', 'Parents are invited to the termly parent-teacher meeting to discuss student progress.'],
            ['Admission Open for New Session', 'Admission', 'Online admission for the upcoming academic session is now open. Apply early to secure your seat.'],
            ['School Closed for Public Holiday', 'Holiday', 'The school will remain closed for the upcoming public holiday. Regular classes resume the following day.'],
            ['Science Fair Registration', 'General', 'Registration for the inter-school science fair is now open for Grade 6 to Grade 10 students.'],
            ['Library Book Return Notice', 'General', 'All borrowed library books must be returned before the end of this month to avoid fines.'],
            ['Urgent: Revised Bus Timings', 'Urgent', 'Please note the revised school bus timings effective from Monday. Contact the office for details.'],
            ['Annual Cultural Festival', 'Event', 'Our annual cultural festival is back. Auditions for performances begin next week.'],
            ['Scholarship Applications Invited', 'Academic', 'Applications for merit-based scholarships are now open for eligible students.'],
        ];

        foreach ($noticeTemplates as $index => [$title, $category, $body]) {
            Notice::updateOrCreate(['title' => $title], [
                'category' => $category,
                'description' => $body . "\n\n" . $this->faker->paragraph(3),
                'is_published' => true,
                'published_at' => now()->subDays($index * 2),
                'user_id' => $adminId,
            ]);
        }

        $eventDefinitions = [
            ['Annual Sports Day', now()->addDays(12), '10:00', 'Main Sports Ground'],
            ['Science & Innovation Fair', now()->addDays(24), '09:30', 'Science Block'],
            ['Parent-Teacher Meeting', now()->addDays(6), '14:00', 'Classrooms'],
            ['Cultural Festival', now()->addDays(40), '17:00', 'Auditorium'],
            ['Inter-School Quiz Championship', now()->addDays(18), '11:00', 'Library Hall'],
            ['Graduation Ceremony', now()->addDays(70), '16:00', 'Auditorium'],
            ['Independence Day Celebration', now()->subDays(20), '08:00', 'Front Lawn'],
            ['Art & Craft Exhibition', now()->subDays(45), '10:00', 'Arts Room'],
        ];

        foreach ($eventDefinitions as [$title, $date, $time, $location]) {
            Event::updateOrCreate(['title' => $title], [
                'description' => $this->faker->paragraph(4),
                'image' => $this->sampleImage('event-' . strtolower(preg_replace('/[^a-z]+/i', '-', $title)), $title, ['#0EA5E9', '#2563EB']),
                'event_date' => $date->toDateString(),
                'event_time' => $time,
                'location' => $location,
                'is_published' => true,
            ]);
        }

        $newsDefinitions = [
            ['Students Excel at Regional Science Fair', 'Achievement'],
            ['New Computer Lab Officially Opened', 'Campus'],
            ['Football Team Wins District Championship', 'Sports'],
            ['Cultural Week Celebrates Diversity', 'Cultural'],
            ['Library Expands Digital Collection', 'Campus'],
            ['Alumni Return for Career Day', 'General'],
            ['New Academic Session Begins', 'Academic'],
            ['Green Initiative: 500 Trees Planted', 'General'],
        ];

        foreach ($newsDefinitions as $index => [$title, $category]) {
            News::updateOrCreate(['title' => $title], [
                'category' => $category,
                'description' => $this->faker->paragraph(5) . "\n\n" . $this->faker->paragraph(4),
                'featured_image' => $this->sampleImage('news-' . $index, $title, ['#F59E0B', '#DC2626']),
                'is_published' => true,
                'published_at' => now()->subDays($index * 3),
            ]);
        }

        $albumDefinitions = [
            ['Campus Life', 'Campus'],
            ['Annual Sports Day 2025', 'Sports'],
            ['Cultural Festival', 'Cultural'],
            ['Science Fair Highlights', 'Academic'],
            ['Classroom Moments', 'Campus'],
        ];

        foreach ($albumDefinitions as $aIndex => [$title, $category]) {
            $album = GalleryAlbum::updateOrCreate(['title' => $title], [
                'category' => $category,
                'description' => 'A collection of photographs capturing ' . $title . ' at My School.',
                'cover_image' => $this->sampleImage('album-' . $aIndex . '-cover', $title, ['#2563EB', '#0EA5E9']),
            ]);

            for ($i = 1; $i <= 6; $i++) {
                $album->images()->updateOrCreate(
                    ['image' => 'samples/album-' . $aIndex . '-' . $i . '.svg'],
                    ['caption' => $title . ' — photo ' . $i]
                );
                Storage::disk('public')->put('samples/album-' . $aIndex . '-' . $i . '.svg', $this->svg($title . ' #' . $i, ['#3B82F6', '#1E40AF']));
            }
        }
    }

    private function seedAdmissionsAndMessages(): void
    {
        $classes = SchoolClass::ordered()->pluck('name')->all();

        for ($i = 1; $i <= 10; $i++) {
            $status = $this->faker->randomElement(['pending', 'pending', 'approved', 'rejected']);

            Admission::updateOrCreate(
                ['application_id' => 'ADM-2026-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'student_name' => $this->faker->name(),
                    'date_of_birth' => now()->subYears($this->faker->numberBetween(6, 15))->toDateString(),
                    'gender' => $this->faker->randomElement(['male', 'female', 'other']),
                    'applying_class' => $this->faker->randomElement($classes),
                    'previous_school' => $this->faker->company(),
                    'guardian_name' => $this->faker->name(),
                    'guardian_phone' => '+1 (555) ' . $this->faker->numerify('###-####'),
                    'email' => $this->faker->safeEmail(),
                    'address' => $this->faker->streetAddress() . ', Springfield',
                    'additional_info' => $this->faker->sentence(12),
                    'status' => $status,
                ]
            );
        }

        $subjects = ['Admission enquiry for Grade 6', 'Request for campus tour', 'Fee structure question', 'Transport facility enquiry', 'Sports programme details', 'Transfer certificate request', 'Scholarship information', 'Parent portal access'];

        foreach ($subjects as $index => $subject) {
            Contact::updateOrCreate(
                ['email' => 'parent' . ($index + 1) . '@example.com'],
                [
                    'name' => $this->faker->name(),
                    'phone' => '+1 (555) ' . $this->faker->numerify('###-####'),
                    'subject' => $subject,
                    'message' => $this->faker->paragraph(3),
                    'is_read' => $index > 3,
                ]
            );
        }
    }

    /* ------------------------------------------------------------------ */

    /**
     * Generate (once) an SVG sample image on the public disk.
     *
     * @param  array{0:string,1:string}  $colors
     */
    private function sampleImage(string $slug, string $label, array $colors): string
    {
        $path = 'samples/' . $slug . '.svg';

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, $this->svg($label, $colors));
        }

        return $path;
    }

    /**
     * @param  array{0:string,1:string}  $colors
     */
    private function svg(string $label, array $colors): string
    {
        $safe = htmlspecialchars(mb_substr($label, 0, 60), ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="600" viewBox="0 0 900 600">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$colors[0]}"/>
      <stop offset="100%" stop-color="{$colors[1]}"/>
    </linearGradient>
  </defs>
  <rect width="900" height="600" fill="url(#g)"/>
  <circle cx="740" cy="120" r="180" fill="rgba(255,255,255,0.12)"/>
  <circle cx="150" cy="500" r="220" fill="rgba(255,255,255,0.09)"/>
  <text x="450" y="290" font-family="Segoe UI, Arial, sans-serif" font-size="42" font-weight="700" fill="#ffffff" text-anchor="middle">My School</text>
  <text x="450" y="345" font-family="Segoe UI, Arial, sans-serif" font-size="26" fill="rgba(255,255,255,0.85)" text-anchor="middle">{$safe}</text>
</svg>
SVG;
    }
}
