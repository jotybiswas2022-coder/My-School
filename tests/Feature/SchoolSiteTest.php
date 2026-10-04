<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Student;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_gallery_album_page_renders(): void
    {
        $admin = \App\Models\User::where('is_admin', true)->first();
        $album = \App\Models\GalleryAlbum::firstOrFail();

        $this->actingAs($admin)->get(route('admin.gallery.show', $album))->assertOk();
    }

    public function test_admin_can_delete_a_settings_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/test-logo.png', 'binary');

        Setting::put('logo', 'settings/test-logo.png');

        $admin = \App\Models\User::where('is_admin', true)->first();

        $this->actingAs($admin)
            ->delete(route('admin.settings.images.destroy', 'logo'))
            ->assertRedirect();

        $this->assertNull(Setting::getRaw('logo'));
        Storage::disk('public')->assertMissing('settings/test-logo.png');
    }

    public function test_unknown_settings_image_key_is_rejected(): void
    {
        $admin = \App\Models\User::where('is_admin', true)->first();

        $this->actingAs($admin)
            ->delete(route('admin.settings.images.destroy', 'school_name'))
            ->assertNotFound();
    }

    public function test_admin_can_upload_the_hero_image_and_it_renders_on_the_homepage(): void
    {
        Storage::fake('public');

        $admin = \App\Models\User::where('is_admin', true)->first();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'school_name' => 'My School',
                'hero_image' => UploadedFile::fake()->createWithContent(
                    'hero.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')
                ),
            ])
            ->assertRedirect();

        $path = Setting::getRaw('hero_image');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);

        $this->get('/')
            ->assertOk()
            ->assertSee(asset('storage/' . $path))
            ->assertSee('hero-card-image', escape: false);
    }

    public function test_homepage_falls_back_to_the_icon_when_no_hero_image_is_set(): void
    {
        $this->assertNull(Setting::getRaw('hero_image'));

        $this->get('/')->assertOk()->assertSee('bi-mortarboard-fill', escape: false);
    }

    public function test_notice_ticker_track_is_wrapped_in_a_clipped_viewport(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('ticker-viewport', $html);
        $this->assertMatchesRegularExpression(
            '/ticker-viewport">\s*<div class="ticker-track">/',
            $html,
        );
    }

    public function test_about_page_uses_the_upgraded_section_layout(): void
    {
        $html = $this->get('/about')->assertOk()->getContent();

        $this->assertStringContainsString('about-story', $html);
        $this->assertStringContainsString('about-stats', $html);
        $this->assertSame(3, substr_count($html, 'class="about-stat"'));
        $this->assertSame(2, substr_count($html, 'class="about-pillar"'));
        $this->assertSame(4, substr_count($html, 'class="tile reveal"'));
        $this->assertSame(6, substr_count($html, 'class="about-why-item reveal"'));
    }

    public function test_facilities_page_groups_the_facilities_into_two_sections(): void
    {
        $html = $this->get('/facilities')->assertOk()->getContent();

        $this->assertStringContainsString('Learning Spaces', $html);
        $this->assertStringContainsString('Campus Life', $html);
        $this->assertSame(2, substr_count($html, 'class="fac-group-title reveal"'));
        $this->assertSame(8, substr_count($html, 'class="tile reveal"'));
        $this->assertStringContainsString('fac-cta', $html);

        $this->assertStringNotContainsString('cta-simple', $html);
    }

    public function test_every_grid_utility_used_in_a_view_has_a_base_layout_rule(): void
    {
        $layout = file_get_contents(resource_path('views/frontend/layouts/app.blade.php'));

        // Strip @media blocks: a class defined only inside a breakpoint is not a base rule.
        $base = preg_replace('/@media[^{]*\{(?:[^{}]*\{[^{}]*\})*[^{}]*\}/s', '', $layout);

        $used = [];
        foreach (File::allFiles(resource_path('views/frontend')) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            if (str_starts_with($relative, 'student/') || str_starts_with($relative, 'layouts/')) {
                continue;
            }

            preg_match_all('/class="([^"]*)"/', $file->getContents(), $attributes);

            foreach ($attributes[1] as $classAttribute) {
                preg_match_all('/\b(grid-[a-z0-9-]+)\b/', $classAttribute, $matches);
                $used += array_fill_keys($matches[1], true);
            }
        }

        $this->assertNotEmpty($used);

        foreach (array_keys($used) as $class) {
            $this->assertMatchesRegularExpression(
                '/\.' . preg_quote($class, '/') . '\s*[,{]/',
                $base,
                $class.' is used in a view but has no base rule in the frontend layout.',
            );
        }
    }

    public function test_english_and_bengali_lang_files_expose_the_same_keys(): void
    {
        $en = array_keys(Arr::dot(require lang_path('en/ui.php')));
        $bn = array_keys(Arr::dot(require lang_path('bn/ui.php')));

        $this->assertSame([], array_diff($en, $bn), 'Missing from bn/ui.php: '.implode(', ', array_diff($en, $bn)));
        $this->assertSame([], array_diff($bn, $en), 'Missing from en/ui.php: '.implode(', ', array_diff($bn, $en)));
    }

    public function test_404_page_renders(): void
    {
        $this->get('/this-page-does-not-exist')->assertNotFound();
    }
}
