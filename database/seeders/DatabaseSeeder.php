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
        Setting::put('school_name_bn', 'মাই স্কুল');
        Setting::put('tagline', 'Empowering Students. Inspiring Futures.');
        Setting::put('tagline_bn', 'শিক্ষার্থীদের ক্ষমতায়ন, ভবিষ্যতের অনুপ্রেরণা।');
        Setting::put('email', 'info@myschool.edu');
        Setting::put('phone', '+1 (555) 123-4567');
        Setting::put('address', '123 Education Avenue, Springfield, ST 12345');
        Setting::put('address_bn', '১২৩ এডুকেশন অ্যাভিনিউ, স্প্রিংফিল্ড, এসটি ১২৩৪৫');
        Setting::put('office_hours', 'Monday - Friday, 8:00 AM - 4:00 PM');
        Setting::put('office_hours_bn', 'সোমবার - শুক্রবার, সকাল ৮:০০ - বিকাল ৪:০০');
        Setting::put('established_year', '1998');
        Setting::put('principal_name', 'Dr. Sarah Mitchell');
        Setting::put('principal_name_bn', 'ড. সারাহ মিচেল');
        Setting::put('principal_designation', 'Principal');
        Setting::put('principal_designation_bn', 'অধ্যক্ষ');
        Setting::put('principal_message', "Welcome to My School. For more than two decades we have been a place where curiosity is celebrated and character is built.\n\nOur dedicated teachers, modern facilities and vibrant community create an environment where every student can discover their strengths. We believe education is more than examinations - it is about becoming thoughtful, resilient and compassionate people.\n\nI warmly invite you to visit our campus, meet our teachers and see for yourself what makes our school special.");
        Setting::put('principal_message_bn', "মাই স্কুলে আপনাকে স্বাগতম। দুই দশকেরও বেশি সময় ধরে আমরা এমন একটি জায়গা, যেখানে কৌতূহলকে উদযাপন করা হয় এবং চরিত্র গঠিত হয়।\n\nআমাদের নিবেদিতপ্রাণ শিক্ষকগণ, আধুনিক সুবিধা ও প্রাণবন্ত পরিবেশ এমন এক পরিবেশ তৈরি করে, যেখানে প্রতিটি শিক্ষার্থী নিজের শক্তিকে আবিষ্কার করতে পারে। আমরা বিশ্বাস করি, শিক্ষা শুধু পরীক্ষার মধ্যে সীমাবদ্ধ নয় — এটি চিন্তাশীল, স্থিতিস্থাপক ও সহানুভূতিশীল মানুষ হয়ে ওঠার বিষয়।\n\nআমি আপনাকে আন্তরিকভাবে আমাদের ক্যাম্পাস পরিদর্শন, আমাদের শিক্ষকদের সাথে সাক্ষাৎ এবং নিজে দেখার জন্য আমন্ত্রণ জানাচ্ছি, কী আমাদের বিদ্যালয়কে বিশেষ করে তোলে।");
        Setting::put('about_description', 'My School is a modern learning community dedicated to academic excellence, creativity and character development. Since 1998 we have helped thousands of students discover their potential in a caring and inclusive environment.');
        Setting::put('about_description_bn', 'মাই স্কুল একটি আধুনিক শিক্ষা প্রতিষ্ঠান, যা একাডেমিক উৎকর্ষ, সৃজনশীলতা ও চরিত্র বিকাশে নিবেদিত। ১৯৯৮ সাল থেকে আমরা যত্নশীল ও অন্তর্ভুক্তিমূলক পরিবেশে হাজারো শিক্ষার্থীকে নিজেদের সম্ভাবনা আবিষ্কারে সহায়তা করে আসছি।');
        Setting::put('mission', 'To provide an inspiring, future-ready education that develops confident, responsible and compassionate global citizens.');
        Setting::put('mission_bn', 'আত্মবিশ্বাসী, দায়িত্বশীল ও সহানুভূতিশীল বৈশ্বিক নাগরিক গড়ে তুলতে অনুপ্রেরণাদায়ী ও ভবিষ্যত-প্রস্তুত শিক্ষা প্রদান করা।');
        Setting::put('vision', 'To be a leading school recognised for academic innovation, inclusion and character formation.');
        Setting::put('vision_bn', 'একাডেমিক উদ্ভাবন, অন্তর্ভুক্তি ও চরিত্র গঠনের জন্য স্বীকৃত একটি শীর্ষস্থানীয় বিদ্যালয় হওয়া।');
        Setting::put('footer_text', '© ' . date('Y') . ' My School. All rights reserved.');
        Setting::put('footer_text_bn', '© ' . date('Y') . ' মাই স্কুল। সর্বস্বত্ব সংরক্ষিত।');
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
        $namesBn = ['প্রথম শ্রেণী', 'দ্বিতীয় শ্রেণী', 'তৃতীয় শ্রেণী', 'চতুর্থ শ্রেণী', 'পঞ্চম শ্রেণী', 'ষষ্ঠ শ্রেণী', 'সপ্তম শ্রেণী', 'অষ্টম শ্রেণী', 'নবম শ্রেণী', 'দশম শ্রেণী'];

        foreach ($names as $index => $name) {
            $nameBn = $namesBn[$index];
            $class = SchoolClass::updateOrCreate(
                ['name' => $name],
                [
                    'code' => 'G' . ($index + 1),
                    'name_bn' => $nameBn,
                    'description' => 'A balanced curriculum focused on strong foundations and holistic growth for ' . $name . ' students.',
                    'description_bn' => $nameBn . ' শিক্ষার্থীদের জন্য মজবুত ভিত্তি ও সার্বিক বিকাশ নিশ্চিত করে এমন একটি সুষম পাঠ্যক্রম।',
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

        // Bengali name/designation/department/qualification plus the numeric
        // experience (Bengali digits can't be cast to int for join dates).
        $teachersBn = [
            0 => ['name' => 'ড. সারাহ মিচেল', 'designation' => 'অধ্যক্ষ', 'department' => 'প্রশাসন', 'qualification' => 'শিক্ষায় পিএইচ.ডি', 'experience' => '২৪ বছর', 'years' => 24],
            1 => ['name' => 'জনাব জেমস কার্টার', 'designation' => 'উপ-অধ্যক্ষ', 'department' => 'প্রশাসন', 'qualification' => 'এম.এড, বি.এ', 'experience' => '১৮ বছর', 'years' => 18],
            2 => ['name' => 'জনাবা এমিলি নগুয়েন', 'designation' => 'সিনিয়র শিক্ষক', 'department' => 'বিজ্ঞান', 'qualification' => 'পদার্থবিজ্ঞানে এম.এসসি, বি.এড', 'experience' => '১২ বছর', 'years' => 12],
            3 => ['name' => 'জনাব ডেভিড ওকাফর', 'designation' => 'সিনিয়র শিক্ষক', 'department' => 'গণিত', 'qualification' => 'গণিতে এম.এসসি', 'experience' => '১৪ বছর', 'years' => 14],
            4 => ['name' => 'জনাবা আয়েশা রহমান', 'designation' => 'শিক্ষক', 'department' => 'ইংরেজি', 'qualification' => 'ইংরেজিতে এম.এ, বি.এড', 'experience' => '৯ বছর', 'years' => 9],
            5 => ['name' => 'জনাব লুকাস সিলভা', 'designation' => 'শিক্ষক', 'department' => 'বিজ্ঞান', 'qualification' => 'রসায়নে এম.এসসি', 'experience' => '৭ বছর', 'years' => 7],
            6 => ['name' => 'জনাবা হান্না লি', 'designation' => 'শিক্ষক', 'department' => 'কম্পিউটার বিজ্ঞান', 'qualification' => 'কম্পিউটার বিজ্ঞানে বি.এসসি', 'experience' => '৬ বছর', 'years' => 6],
            7 => ['name' => 'জনাব মোহাম্মদ আলী', 'designation' => 'শিক্ষক', 'department' => 'গণিত', 'qualification' => 'গণিতে বি.এসসি, বি.এড', 'experience' => '১০ বছর', 'years' => 10],
            8 => ['name' => 'জনাবা গ্রেস থম্পসন', 'designation' => 'শিক্ষক', 'department' => 'মানবিক', 'qualification' => 'ইতিহাসে এম.এ', 'experience' => '৮ বছর', 'years' => 8],
            9 => ['name' => 'জনাব রায়ান প্যাটেল', 'designation' => 'শিক্ষক', 'department' => 'শারীরিক শিক্ষা', 'qualification' => 'বি.পি.এড', 'experience' => '১১ বছর', 'years' => 11],
            10 => ['name' => 'জনাবা সোফিয়া রসি', 'designation' => 'শিক্ষক', 'department' => 'ভাষা', 'qualification' => 'ফরাসি ভাষায় এম.এ', 'experience' => '৫ বছর', 'years' => 5],
            11 => ['name' => 'জনাব ড্যানিয়েল কিম', 'designation' => 'শিক্ষক', 'department' => 'চারুকলা', 'qualification' => 'বি.এফ.এ', 'experience' => '৬ বছর', 'years' => 6],
            12 => ['name' => 'জনাবা ফাতিমা নূর', 'designation' => 'শিক্ষক', 'department' => 'ইংরেজি', 'qualification' => 'এম.এড, বি.এ', 'experience' => '১৩ বছর', 'years' => 13],
            13 => ['name' => 'জনাব ইথান ব্রুকস', 'designation' => 'শিক্ষক', 'department' => 'মানবিক', 'qualification' => 'ভূগোলে এম.এ', 'experience' => '৯ বছর', 'years' => 9],
        ];

        $photo = $this->sampleImage('teacher-portrait', 'Academic Staff', ['#2563EB', '#1E40AF']);

        foreach ($teachers as $index => [$name, $designation, $department, $qualification, $experience]) {
            $bn = $teachersBn[$index];
            Teacher::updateOrCreate(
                ['email' => strtolower(str_replace([' ', '.', "'"], ['', '', ''], $name)) . '@myschool.edu'],
                [
                    'name' => $name,
                    'name_bn' => $bn['name'],
                    'designation' => $designation,
                    'designation_bn' => $bn['designation'],
                    'department' => $department,
                    'department_bn' => $bn['department'],
                    'qualification' => $qualification,
                    'qualification_bn' => $bn['qualification'],
                    'experience' => $bn['experience'],
                    'phone' => '+1 (555) ' . str_pad((string) (200 + $index), 3, '0', STR_PAD_LEFT) . '-' . $this->faker->numerify('####'),
                    'bio' => $name . ' has been part of the My School community for ' . $experience . '. ' . $this->faker->paragraph(3),
                    'bio_bn' => $bn['name'] . ' গত ' . $bn['experience'] . ' ধরে মাই স্কুল পরিবারের সাথে যুক্ত। তিনি শিক্ষার্থীদের প্রতি নিবেদিতপ্রাণ, অভিজ্ঞ ও আন্তরিক একজন শিক্ষক।',
                    'photo' => $photo,
                    'join_date' => now()->subYears($bn['years'])->startOfYear(),
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
            ['English Language', 'ইংরেজি ভাষা', 'ENG', 'English'],
            ['Mathematics', 'গণিত', 'MATH', 'Mathematics'],
            ['General Science', 'সাধারণ বিজ্ঞান', 'SCI', 'Science'],
            ['Social Studies', 'সমাজ অধ্যয়ন', 'SOC', 'Humanities'],
            ['Computer Science', 'কম্পিউটার বিজ্ঞান', 'COMP', 'Computer Science'],
            ['Physical Education', 'শারীরিক শিক্ষা', 'PE', 'Physical Education'],
            ['Fine Arts', 'চারুকলা', 'ART', 'Arts'],
            ['Second Language', 'দ্বিতীয় ভাষা', 'LANG', 'Languages'],
        ];

        foreach ($definitions as [$name, $nameBn, $code, $department]) {
            $teacher = $teachers->firstWhere('department', $department) ?? $teachers->first();

            Subject::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'name_bn' => $nameBn,
                    'description' => 'A comprehensive ' . $name . ' programme developing core knowledge, critical thinking and practical skills.',
                    'description_bn' => $nameBn . ' বিষয়ে গভীর জ্ঞান, সমালোচনামূলক চিন্তা ও ব্যবহারিক দক্ষতা বিকাশে সহায়ক একটি পূর্ণাঙ্গ কর্মসূচি।',
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
            ['First Term Examination', 'প্রথম সাময়িক পরীক্ষা', 'Written', 'লিখিত', $previous, now()->subMonths(8), now()->subMonths(7), true],
            ['Mid Term Examination', 'মধ্যমেয়াদি পরীক্ষা', 'Written', 'লিখিত', $current, now()->subMonths(3), now()->subMonths(3)->addDays(10), true],
            ['Final Term Examination', 'বার্ষিক পরীক্ষা', 'Written', 'লিখিত', $current, now()->addMonth(), now()->addMonth()->addDays(12), false],
        ];

        foreach ($definitions as [$name, $nameBn, $type, $typeBn, $session, $start, $end, $published]) {
            Exam::updateOrCreate(['name' => $name], [
                'name_bn' => $nameBn,
                'exam_type' => $type,
                'exam_type_bn' => $typeBn,
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
            ['Annual Sports Day Announcement', 'বার্ষিক ক্রীড়া প্রতিযোগিতার ঘোষণা', 'Event', 'আগামী মাসে আমাদের বার্ষিক ক্রীড়া প্রতিযোগিতা অনুষ্ঠিত হবে। সকল শিক্ষার্থীকে অন্তত একটি ইভেন্টে অংশগ্রহণ করতে উৎসাহিত করা হচ্ছে।'],
            ['Mid Term Examination Schedule', 'মধ্যমেয়াদি পরীক্ষার সময়সূচি', 'Exam', 'মধ্যমেয়াদি পরীক্ষার সময়সূচি প্রকাশ করা হয়েছে। অনুগ্রহ করে শ্রেণি শিক্ষকের কাছ থেকে প্রবেশপত্র সংগ্রহ করুন।'],
            ['Parent-Teacher Meeting', 'অভিভাবক-শিক্ষক সভা', 'Academic', 'শিক্ষার্থীদের অগ্রগতি নিয়ে আলোচনার জন্য অভিভাবকদের ত্রৈমাসিক অভিভাবক-শিক্ষক সভায় আমন্ত্রণ জানানো হচ্ছে।'],
            ['Admission Open for New Session', 'নতুন শিক্ষাবর্ষে ভর্তি শুরু', 'Admission', 'আগামী শিক্ষাবর্ষের অনলাইন ভর্তি প্রক্রিয়া এখন চালু। আসন নিশ্চিত করতে তাড়াতাড়ি আবেদন করুন।'],
            ['School Closed for Public Holiday', 'সরকারি ছুটিতে বিদ্যালয় বন্ধ', 'Holiday', 'আগামী সরকারি ছুটির কারণে বিদ্যালয় বন্ধ থাকবে। পরবর্তী দিন নিয়মিত ক্লাস শুরু হবে।'],
            ['Science Fair Registration', 'বিজ্ঞান মেলায় নিবন্ধন', 'General', 'ষষ্ঠ থেকে দশম শ্রেণীর শিক্ষার্থীদের জন্য আন্তঃস্কুল বিজ্ঞান মেলার নিবন্ধন এখন চালু।'],
            ['Library Book Return Notice', 'গ্রন্থাগারের বই ফেরতের নোটিশ', 'General', 'জরিমানা এড়াতে সব ধার করা গ্রন্থাগারের বই এই মাস শেষ হবার আগেই ফেরত দিতে হবে।'],
            ['Urgent: Revised Bus Timings', 'জরুরি: সংশোধিত বাস সময়সূচি', 'Urgent', 'সোমবার থেকে কার্যকর স্কুল বাসের সংশোধিত সময়সূচি লক্ষ্য করুন। বিস্তারিত জানতে অফিসে যোগাযোগ করুন।'],
            ['Annual Cultural Festival', 'বার্ষিক সাংস্কৃতিক উৎসব', 'Event', 'আমাদের বার্ষিক সাংস্কৃতিক উৎসব ফিরে এসেছে। আগামী সপ্তাহে পরিবেশনার জন্য অডিশন শুরু হবে।'],
            ['Scholarship Applications Invited', 'বৃত্তির আবেদন আহ্বান', 'Academic', 'যোগ্য শিক্ষার্থীদের জন্য মেধাভিত্তিক বৃত্তির আবেদন এখন চালু।'],
        ];

        foreach ($noticeTemplates as $index => [$title, $titleBn, $category, $body]) {
            Notice::updateOrCreate(['title' => $title], [
                'title_bn' => $titleBn,
                'category' => $category,
                'description' => $body . "\n\n" . $this->faker->paragraph(3),
                'description_bn' => $body . "\n\nবিস্তারিত জানতে বিদ্যালয় অফিসে যোগাযোগ করুন।",
                'is_published' => true,
                'published_at' => now()->subDays($index * 2),
                'user_id' => $adminId,
            ]);
        }

        $eventDefinitions = [
            ['Annual Sports Day', 'বার্ষিক ক্রীড়া প্রতিযোগিতা', now()->addDays(12), '10:00', 'Main Sports Ground', 'প্রধান ক্রীড়া মাঠ'],
            ['Science & Innovation Fair', 'বিজ্ঞান ও উদ্ভাবন মেলা', now()->addDays(24), '09:30', 'Science Block', 'বিজ্ঞান ভবন'],
            ['Parent-Teacher Meeting', 'অভিভাবক-শিক্ষক সভা', now()->addDays(6), '14:00', 'Classrooms', 'শ্রেণিকক্ষ'],
            ['Cultural Festival', 'সাংস্কৃতিক উৎসব', now()->addDays(40), '17:00', 'Auditorium', 'মিলনায়তন'],
            ['Inter-School Quiz Championship', 'আন্তঃস্কুল কুইজ চ্যাম্পিয়নশিপ', now()->addDays(18), '11:00', 'Library Hall', 'লাইব্রেরি হল'],
            ['Graduation Ceremony', 'শিক্ষাসমাপনী অনুষ্ঠান', now()->addDays(70), '16:00', 'Auditorium', 'মিলনায়তন'],
            ['Independence Day Celebration', 'স্বাধীনতা দিবস উদযাপন', now()->subDays(20), '08:00', 'Front Lawn', 'সম্মুখ লন'],
            ['Art & Craft Exhibition', 'শিল্প ও কারুশিল্প প্রদর্শনী', now()->subDays(45), '10:00', 'Arts Room', 'চারুকলা কক্ষ'],
        ];

        foreach ($eventDefinitions as [$title, $titleBn, $date, $time, $location, $locationBn]) {
            Event::updateOrCreate(['title' => $title], [
                'title_bn' => $titleBn,
                'description' => $this->faker->paragraph(4),
                'description_bn' => $titleBn . " উপলক্ষে সকল শিক্ষার্থী, অভিভাবক ও শিক্ষকবৃন্দের অংশগ্রহণ কাম্য। বিস্তারিত তথ্য বিদ্যালয় অফিসে পাওয়া যাবে।",
                'image' => $this->sampleImage('event-' . strtolower(preg_replace('/[^a-z]+/i', '-', $title)), $title, ['#0EA5E9', '#2563EB']),
                'event_date' => $date->toDateString(),
                'event_time' => $time,
                'location' => $location,
                'location_bn' => $locationBn,
                'is_published' => true,
            ]);
        }

        $newsDefinitions = [
            ['Students Excel at Regional Science Fair', 'আঞ্চলিক বিজ্ঞান মেলায় শিক্ষার্থীদের অসামান্য সাফল্য', 'Achievement'],
            ['New Computer Lab Officially Opened', 'নতুন কম্পিউটার ল্যাব আনুষ্ঠানিকভাবে চালু', 'Campus'],
            ['Football Team Wins District Championship', 'ফুটবল দল জেলা চ্যাম্পিয়নশিপে বিজয়ী', 'Sports'],
            ['Cultural Week Celebrates Diversity', 'সাংস্কৃতিক সপ্তাহে বৈচিত্র্যের উদযাপন', 'Cultural'],
            ['Library Expands Digital Collection', 'গ্রন্থাগারের ডিজিটাল সংগ্রহ সম্প্রসারিত', 'Campus'],
            ['Alumni Return for Career Day', 'ক্যারিয়ার দিবসে প্রাক্তন শিক্ষার্থীদের শুভাগমন', 'General'],
            ['New Academic Session Begins', 'নতুন শিক্ষাবর্ষের সূচনা', 'Academic'],
            ['Green Initiative: 500 Trees Planted', 'সবুজ উদ্যোগ: ৫০০ গাছ রোপণ', 'General'],
        ];

        foreach ($newsDefinitions as $index => [$title, $titleBn, $category]) {
            News::updateOrCreate(['title' => $title], [
                'title_bn' => $titleBn,
                'category' => $category,
                'description' => $this->faker->paragraph(5) . "\n\n" . $this->faker->paragraph(4),
                'description_bn' => $titleBn . " — মাই স্কুলের শিক্ষার্থী ও শিক্ষকদের যৌথ প্রচেষ্টায় এই অর্জন সম্ভব হয়েছে। বিস্তারিত জানতে বিদ্যালয়ের সংবাদ বিভাগে যোগাযোগ করুন।",
                'featured_image' => $this->sampleImage('news-' . $index, $title, ['#F59E0B', '#DC2626']),
                'is_published' => true,
                'published_at' => now()->subDays($index * 3),
            ]);
        }

        $albumDefinitions = [
            ['Campus Life', 'ক্যাম্পাস জীবন', 'Campus'],
            ['Annual Sports Day 2025', 'বার্ষিক ক্রীড়া প্রতিযোগিতা ২০২৫', 'Sports'],
            ['Cultural Festival', 'সাংস্কৃতিক উৎসব', 'Cultural'],
            ['Science Fair Highlights', 'বিজ্ঞান মেলার উল্লেখযোগ্য মুহূর্ত', 'Academic'],
            ['Classroom Moments', 'শ্রেণিকক্ষের মুহূর্ত', 'Campus'],
        ];

        foreach ($albumDefinitions as $aIndex => [$title, $titleBn, $category]) {
            $album = GalleryAlbum::updateOrCreate(['title' => $title], [
                'title_bn' => $titleBn,
                'category' => $category,
                'description' => 'A collection of photographs capturing ' . $title . ' at My School.',
                'description_bn' => 'মাই স্কুলে ' . $titleBn . ' বিষয়ক মুহূর্তগুলো ধারণ করা আলোকচিত্রের সংগ্রহ।',
                'cover_image' => $this->sampleImage('album-' . $aIndex . '-cover', $title, ['#2563EB', '#0EA5E9']),
            ]);

            for ($i = 1; $i <= 6; $i++) {
                $album->images()->updateOrCreate(
                    ['image' => 'samples/album-' . $aIndex . '-' . $i . '.svg'],
                    ['caption' => $titleBn . ' — ছবি ' . $i]
                );
                Storage::disk('public')->put('samples/album-' . $aIndex . '-' . $i . '.svg', $this->svg($titleBn . ' #' . $i, ['#3B82F6', '#1E40AF']));
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
