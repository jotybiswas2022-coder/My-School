<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Contact;
use App\Models\Student;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SchoolSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public static function publicPages(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'principal' => ['/principal'],
            'teachers' => ['/teachers'],
            'students' => ['/students'],
            'academics' => ['/academics'],
            'classes' => ['/classes'],
            'subjects' => ['/subjects'],
            'facilities' => ['/facilities'],
            'notices' => ['/notices'],
            'events' => ['/events'],
            'news' => ['/news'],
            'gallery' => ['/gallery'],
            'results' => ['/results'],
            'admission' => ['/admission'],
            'admission-info' => ['/admission/information'],
            'admission-status' => ['/admission/status'],
            'contact' => ['/contact'],
            'student-login' => ['/student/login'],
            'admin-login' => ['/admin/login'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render(string $path): void
    {
        $this->get($path)->assertOk();
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $this->post('/admin/login', [
            'email' => 'admin@myschool.edu',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs(\App\Models\User::where('is_admin', true)->first())
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_admin_routes_are_protected(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
    }

    public function test_admin_crud_pages_render(): void
    {
        $admin = \App\Models\User::where('is_admin', true)->first();

        foreach ([
            '/admin/students',
            '/admin/students/create',
            '/admin/teachers',
            '/admin/teachers/create',
            '/admin/classes',
            '/admin/classes/create',
            '/admin/subjects',
            '/admin/subjects/create',
            '/admin/attendance',
            '/admin/attendance/report',
            '/admin/sessions',
            '/admin/exams',
            '/admin/exams/create',
            '/admin/results',
            '/admin/results/entry',
            '/admin/notices',
            '/admin/notices/create',
            '/admin/events',
            '/admin/events/create',
            '/admin/news',
            '/admin/news/create',
            '/admin/gallery',
            '/admin/admissions',
            '/admin/messages',
            '/admin/settings',
        ] as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }

    public function test_student_can_login_and_view_dashboard(): void
    {
        $student = Student::first();

        $this->post('/student/login', [
            'student_id' => $student->student_id,
            'password' => 'password',
        ])->assertRedirect(route('student.dashboard'));

        $this->actingAs($student, 'student')->get('/student/dashboard')->assertOk();
        $this->actingAs($student, 'student')->get('/student/results')->assertOk();
        $this->actingAs($student, 'student')->get('/student/attendance')->assertOk();
        $this->actingAs($student, 'student')->get('/student/profile')->assertOk();
        $this->actingAs($student, 'student')->get('/student/notices')->assertOk();
        $this->actingAs($student, 'student')->get('/student/academics')->assertOk();
    }

    public function test_admission_application_can_be_submitted(): void
    {
        $response = $this->post('/admission', [
            'student_name' => 'Jane Applicant',
            'date_of_birth' => '2015-05-10',
            'gender' => 'female',
            'applying_class' => 'Grade 6',
            'guardian_name' => 'John Applicant',
            'guardian_phone' => '+1 555 000 1111',
            'email' => 'jane@example.com',
            'address' => '12 Example Street, Springfield',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admissions', ['student_name' => 'Jane Applicant', 'status' => 'pending']);
    }

    public function test_admission_validation_errors(): void
    {
        $this->post('/admission', ['student_name' => 'A'])
            ->assertSessionHasErrors(['student_name', 'date_of_birth', 'gender', 'applying_class', 'guardian_name', 'guardian_phone', 'address']);
    }

    public function test_contact_message_is_stored(): void
    {
        $this->post('/contact', [
            'name' => 'Parent One',
            'email' => 'parent@example.com',
            'subject' => 'Admission question',
            'message' => 'I would like to know more about the admission process.',
        ])->assertRedirect(route('contact.page'));

        $this->assertDatabaseHas('contacts', ['email' => 'parent@example.com']);
        $this->assertSame('Admission question', Contact::where('email', 'parent@example.com')->value('subject'));
    }

    public function test_result_search_returns_marksheet(): void
    {
        $exam = \App\Models\Exam::where('is_published', true)->first();
        $student = Student::first();

        $this->post('/results/search', [
            'student_id' => $student->student_id,
            'exam_id' => $exam->id,
        ])->assertOk()->assertSee($student->name);
    }

    public function test_admin_can_create_a_student(): void
    {
        $admin = \App\Models\User::where('is_admin', true)->first();

        $this->actingAs($admin)->post('/admin/students', [
            'student_id' => 'STU-NEW-001',
            'name' => 'New Student',
            'is_active' => '1',
        ])->assertRedirect(route('admin.students.index'));

        $this->assertDatabaseHas('students', ['student_id' => 'STU-NEW-001']);
    }

    public function test_404_page_renders(): void
    {
        $this->get('/this-page-does-not-exist')->assertNotFound();
    }
}
