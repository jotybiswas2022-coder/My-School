<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Contact;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
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

    public function test_admin_can_upload_multiple_hero_slider_images_that_render_on_the_homepage(): void
    {
        Storage::fake('public');

        $admin = \App\Models\User::where('is_admin', true)->first();
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'school_name' => 'My School',
                'hero_images' => [
                    UploadedFile::fake()->createWithContent('hero-1.png', $png),
                    UploadedFile::fake()->createWithContent('hero-2.png', $png),
                ],
            ])
            ->assertRedirect();

        $slides = json_decode(Setting::getRaw('hero_images'), true);
        $this->assertCount(2, $slides);

        $response = $this->get('/');
        $response->assertOk()->assertSee('hero-slider', escape: false);

        foreach ($slides as $path) {
            Storage::disk('public')->assertExists($path);
            $response->assertSee(asset('storage/' . $path));
        }
    }

    public function test_admin_can_remove_a_single_hero_slider_image(): void
    {
        Storage::fake('public');

        $admin = \App\Models\User::where('is_admin', true)->first();
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'school_name' => 'My School',
                'hero_images' => [
                    UploadedFile::fake()->createWithContent('hero-1.png', $png),
                    UploadedFile::fake()->createWithContent('hero-2.png', $png),
                ],
            ])
            ->assertRedirect();

        $slides = json_decode(Setting::getRaw('hero_images'), true);
        $this->assertCount(2, $slides);

        $this->actingAs($admin)
            ->delete(route('admin.settings.hero-images.destroy', 0))
            ->assertRedirect();

        $this->assertSame([$slides[1]], json_decode(Setting::getRaw('hero_images'), true));
        Storage::disk('public')->assertMissing($slides[0]);

        $this->actingAs($admin)
            ->delete(route('admin.settings.hero-images.destroy', 5))
            ->assertNotFound();
    }

    public function test_legacy_hero_image_is_folded_into_the_slider_list(): void
    {
        Setting::put('hero_image', 'settings/legacy-hero.png');

        $admin = \App\Models\User::where('is_admin', true)->first();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('storage/settings/legacy-hero.png', false);

        $this->assertNull(Setting::getRaw('hero_image'));
        $this->assertSame(['settings/legacy-hero.png'], json_decode(Setting::getRaw('hero_images'), true));
    }

    public function test_homepage_has_no_hero_image_box_when_no_hero_image_is_set(): void
    {
        $this->assertNull(Setting::getRaw('hero_image'));
        $this->assertNull(Setting::getRaw('hero_images'));

        $html = $this->get('/')->assertOk()->getContent();

        // the old card/box markup is gone; the hero image now lives in the background slider
        $this->assertStringNotContainsString('hero-card-image', $html);
        $this->assertStringNotContainsString('bi-mortarboard-fill', $html);
        $this->assertStringContainsString('class="hero"', $html);
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

    public function test_homepage_principal_message_is_a_properly_labelled_quote_panel(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // The quote used to be the section heading, which left the section with no heading at all.
        $this->assertStringContainsString('<h2 class="principal-title">A message from our principal</h2>', $html);
        $this->assertStringContainsString('<figure class="principal-figure">', $html);
        $this->assertStringContainsString('<blockquote class="principal-quote">', $html);
        $this->assertStringContainsString('<figcaption class="principal-sign">', $html);
        $this->assertStringContainsString('principal-frame', $html);
        $this->assertStringContainsString('principal-photo', $html);
        $this->assertStringContainsString(url('/principal'), $html);

        // The old two-column card grid and inline styles are gone.
        $this->assertStringNotContainsString('align-items:center;gap:56px;', $html);
        $this->assertStringNotContainsString('<h2 class="section-title">"', $html);
    }

    public function test_principal_portrait_frame_stays_attached_when_the_grid_collapses(): void
    {
        $layout = file_get_contents(resource_path('views/frontend/layouts/app.blade.php'));

        // The frame is absolutely positioned inside .principal-media, so the media box has to
        // shrink-wrap the portrait. Without justify-self it stretches on phones and the
        // border drifts away from the image.
        $this->assertMatchesRegularExpression(
            '/\.principal-media \{[^}]*justify-self: start;[^}]*\}/',
            $layout,
        );

        $mobile = substr($layout, strpos($layout, '@media (max-width: 720px)'));
        $this->assertStringContainsString('.principal { grid-template-columns: 1fr;', $mobile);
        $this->assertStringContainsString('.principal-photo { width: 172px; }', $mobile);
    }

    public function test_navbar_only_keeps_the_essentials(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $start = strpos($html, '<div class="nav-links" id="navLinks">');
        $this->assertNotFalse($start, 'the navigation should render');
        $nav = substr($html, $start, strpos($html, '<button class="nav-toggle"', $start) - $start);

        // Five essential destinations, not ten.
        $this->assertSame(5, substr_count($nav, 'class="nav-link '));
        foreach (['about', 'academics', 'notices', 'admission', 'contact.page'] as $name) {
            $this->assertStringContainsString(e(route($name)), $nav);
        }

        // The student portal and the language switch stay in the bar.
        $this->assertStringContainsString('nav-cta', $nav);
        $this->assertStringContainsString('lang-switch', $nav);

        // Nothing is orphaned: every dropped link is still one click away in the footer.
        $footer = substr($html, strpos($html, '<footer class="site-footer">'));
        foreach (['teachers', 'events', 'news', 'gallery', 'results'] as $name) {
            $this->assertStringNotContainsString(e(route($name)), $nav);
            $this->assertStringContainsString(e(route($name)), $footer);
        }
    }

    public function test_mobile_navbar_toggle_sits_at_the_far_right(): void
    {
        $layout = str_replace(["\r\n", "\r"], "\n", file_get_contents(resource_path('views/frontend/layouts/app.blade.php')));

        // .nav-links carried the margin-left:auto that pushed the toggle across, but the
        // drawer hides it, so the toggle has to be pushed across on its own.
        $this->assertMatchesRegularExpression(
            '/@media \(max-width: 1399px\) \{.*?\.nav-toggle \{ display: grid; margin-left: auto; \}/s',
            $layout,
        );
        $this->assertMatchesRegularExpression('/@media \(max-width: 1399px\) \{.*?\.nav-links \{ display: none; \}/s', $layout);
        $this->assertMatchesRegularExpression('/\.nav-links \{[^}]*margin-left: auto;/', $layout);
    }

    public function test_footer_has_a_column_for_the_links_dropped_from_the_navbar(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // e() because the heading is escaped in the markup ("News &amp; Updates").
        $this->assertStringContainsString(e(__('ui.footer.news_updates')), $html);

        $footer = substr($html, strpos($html, '<footer class="site-footer">'));
        $this->assertStringContainsString('class="footer-grid"', $footer);
        $this->assertSame(4, substr_count($footer, '<h4>'), 'four link columns plus the brand block');

        // Five columns have to fit on one row on desktop.
        $layout = str_replace(["\r\n", "\r"], "\n", file_get_contents(resource_path('views/frontend/layouts/app.blade.php')));
        $this->assertStringContainsString('grid-template-columns: 1.6fr 1fr 1fr 1fr 1.2fr;', $layout);
    }

    public function test_homepage_admission_cta_matches_the_light_sections_around_it(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $start = strpos($html, '<div class="cta reveal">');
        $this->assertNotFalse($start, 'the admission CTA should render');
        $cta = substr($html, $start, strpos($html, '</section>', $start) - $start);

        $this->assertStringContainsString('class="cta-title">'.__('ui.home.cta_title'), $cta);
        $this->assertStringContainsString('class="cta-text"', $cta);
        $this->assertStringContainsString('class="cta-actions"', $cta);
        $this->assertStringContainsString('btn btn-primary', $cta);
        $this->assertStringContainsString('class="cta-link"', $cta);

        // The old panel was the page's only saturated gradient block, and everything in it
        // was white-on-blue with inline styles, so it clashed with the light sections.
        $this->assertStringNotContainsString('cta-panel', $html);
        $this->assertStringNotContainsString('style=', $cta);
        $this->assertStringNotContainsString('color:#fff', $cta);
        $this->assertStringNotContainsString('#BFDBFE', $cta);

        // The right-hand panel now answers "how do I apply" instead of leaving a blank half.
        $this->assertStringContainsString('class="cta-side-title">'.__('ui.admission.process_title'), $cta);
        $this->assertStringContainsString('<ul class="cta-steps">', $cta);
        $this->assertSame(4, substr_count($cta, 'class="cta-step"'));
        foreach (['process_1', 'process_2', 'process_3', 'process_4'] as $index => $step) {
            // e() because the labels are escaped in the markup ("Decision &amp; enrolment").
            $this->assertStringContainsString('<span class="cta-step-label">'.e(__("ui.admission.$step")).'</span>', $cta);
            $this->assertStringContainsString('aria-hidden="true">'.($index + 1).'</span>', $cta);
        }

        $layout = file_get_contents(resource_path('views/frontend/layouts/app.blade.php'));
        $this->assertStringNotContainsString('.cta-panel', $layout);
        $this->assertMatchesRegularExpression('/\.cta \{[^}]*background: var\(--white\);[^}]*border: 1px solid var\(--border\);/s', $layout);
        $this->assertMatchesRegularExpression('/\.cta-steps \{[^}]*background: var\(--gradient-soft\);/s', $layout);
        $this->assertMatchesRegularExpression('/\.cta-step \{[^}]*min-height: 54px;/', $layout);
    }

    public function test_homepage_facilities_are_grouped_with_short_descriptions(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<div class="fac-home">', $html);
        $this->assertStringContainsString('class="fac-home-title"', $html);
        $this->assertStringContainsString('class="fac-home-item"', $html);

        // Same grouping as the facilities page, so the two pages agree.
        $this->assertSame(2, substr_count($html, 'class="fac-home-title"'));
        $this->assertStringContainsString(__('ui.facilities.learning_spaces'), $html);
        $this->assertStringContainsString(__('ui.facilities.campus_life'), $html);
        $this->assertSame(8, substr_count($html, 'class="fac-home-item"'));

        // The old version was eight centred icon cards that skipped a heading level and
        // carried inline styles on every tile.
        $this->assertStringNotContainsString('card card-hover reveal" style="text-align:center', $html);
        $this->assertStringContainsString('<h4>'.__('ui.facilities.library').'</h4>', $html);
        $this->assertStringContainsString('<small>'.__('ui.facilities.library_short').'</small>', $html);
        $this->assertStringContainsString('<h4>'.__('ui.facilities.transport').'</h4>', $html);
        $this->assertStringContainsString('<small>'.__('ui.facilities.transport_short').'</small>', $html);

        // The short descriptions were already translated but unused until now.
        foreach ([
            'smart_classrooms', 'science_lab', 'computer_lab', 'library',
            'sports_ground', 'transport', 'security', 'cafeteria',
        ] as $slug) {
            $this->assertStringContainsString('<small>'.__("ui.facilities.{$slug}_short").'</small>', $html);
        }

        // All eight boxes share one grid, so a heading splits the rows instead of the two
        // groups drifting out of step with each other.
        $start = strpos($html, '<div class="fac-home">');
        $grid = substr($html, $start, strpos($html, '</section>', $start) - $start);
        $this->assertSame(2, substr_count($grid, '<h3 class="fac-home-title">'));
        $this->assertSame(8, substr_count($grid, '<div class="fac-home-item">'));
        $this->assertLessThan(strpos($grid, '<div class="fac-home-item">'), strpos($grid, '<h3 class="fac-home-title">'));
        $this->assertLessThan(
            strpos($grid, '<h4>'.__('ui.facilities.cafeteria').'</h4>'),
            strpos($grid, '<h3 class="fac-home-title">', strpos($grid, '<h3 class="fac-home-title">') + 1),
        );
    }

    public function test_homepage_facility_boxes_share_one_row_height(): void
    {
        $layout = str_replace(["\r\n", "\r"], "\n", file_get_contents(resource_path('views/frontend/layouts/app.blade.php')));

        // One grid for the whole section: the group headings span the columns and every
        // box gets the same minimum height, so nothing sits lower than its neighbour.
        $this->assertStringContainsString('.fac-home { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));', $layout);
        $this->assertStringContainsString('.fac-home-title {' . "\n" . '            grid-column: 1 / -1;', $layout);
        $this->assertMatchesRegularExpression('/\.fac-home-item \{[^}]*min-height: 100px;/', $layout);
        $this->assertMatchesRegularExpression('/\.fac-home-item h4 \{[^}]*-webkit-line-clamp: 2;/', $layout);
        $this->assertMatchesRegularExpression('/\.fac-home-item small \{[^}]*-webkit-line-clamp: 2;/', $layout);

        // Two columns on smaller screens, but the boxes stay identical there too.
        $this->assertStringContainsString('.fac-home { grid-template-columns: repeat(2, minmax(0, 1fr));', $layout);
        $this->assertMatchesRegularExpression('/@media \(max-width: 720px\).*\.fac-home-item \{ min-height: 96px;/s', $layout);
    }

    public function test_homepage_gallery_titles_are_visible_without_hovering(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<div class="gal">', $html);
        $this->assertStringContainsString('class="gal-item reveal', $html);
        $this->assertStringContainsString('class="gal-media"', $html);
        $this->assertStringContainsString('class="gal-count"', $html);
        $this->assertStringContainsString('class="gal-cap"', $html);
        $this->assertStringContainsString('class="gal-cap-title"', $html);

        // The old masonry hid every album title and photo count behind a hover, and drove
        // the layout with CSS columns plus 900px/600px breakpoints in a page-local block.
        $this->assertStringNotContainsString('class="masonry', $html);
        $this->assertStringNotContainsString('masonry-item', $html);
        $this->assertStringNotContainsString('masonry-overlay', $html);
        // Only the shared layout block should remain; the page-local one is gone.
        $this->assertSame(1, substr_count($html, '<style>'));
        $this->assertStringNotContainsString('@media (max-width: 900px)', $html);
        $this->assertStringNotContainsString('@media (max-width: 600px)', $html);

        $albums = GalleryAlbum::with('images')->latest()->take(5)->get();
        $this->assertSame($albums->count(), substr_count($html, 'class="gal-item reveal'));

        // Every album is one whole-tile link, so no nested buttons remain inside a tile.
        $this->assertSame(
            $albums->count(),
            preg_match_all('/<a href="[^"]*\/gallery\/\d+"\s+class="gal-item reveal[^"]*">/', $html),
        );

        // The photo count is rendered on the tile, not hidden behind the hover overlay.
        preg_match_all('/<a href="[^"]*\/gallery\/\d+"\s+class="gal-item reveal.*?<\/a>/s', $html, $matches);
        $tiles = implode('', $matches[0]);
        $this->assertStringNotContainsString('<button', $tiles);
        foreach ($albums as $album) {
            $this->assertStringContainsString(
                '<i class="bi bi-images" aria-hidden="true"></i> '.$album->images->count(),
                $tiles,
            );
        }

        // A half row is closed by stretching the last tile instead of leaving a hole.
        if ($albums->count() % 3 !== 0) {
            $this->assertStringContainsString('is-wide', $html);
        }
    }

    public function test_homepage_news_leads_with_one_feature_and_thumbnail_rows(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('class="nwslead reveal"', $html);
        $this->assertStringContainsString('class="nwslead-media"', $html);
        $this->assertStringContainsString('nwslead-badge', $html);
        $this->assertStringContainsString('class="nwslead-date" datetime="', $html);
        $this->assertStringContainsString('class="nwslead-title"', $html);
        $this->assertStringContainsString('class="nwslead-desc"', $html);
        $this->assertStringContainsString('class="nwslead-go"', $html);
        $this->assertStringContainsString('class="nwsgrid"', $html);
        $this->assertStringContainsString('class="nwsmini reveal"', $html);
        $this->assertStringContainsString('class="nwsmini-media"', $html);
        $this->assertStringContainsString('class="nwsmini-title"', $html);

        // The old version was three identical cards, each with its own "Read more" button.
        $this->assertStringNotContainsString('style="margin-bottom:40px;align-items:flex-end;"', $html);
        $this->assertSame(3, substr_count($html, '<h3 class="nwslead-title">') + substr_count($html, '<h3 class="nwsmini-title">'));

        $articles = News::published()->take(3)->get();
        $lead = $articles->first();

        // The lead is the newest article and there is exactly one of it.
        $this->assertStringContainsString(route('news.show', $lead), $html);
        $this->assertSame(1, substr_count($html, 'class="nwslead reveal"'));
        $this->assertSame($articles->count() - 1, substr_count($html, 'class="nwsmini reveal"'));

        // Every item is a whole-card link, so no nested buttons are left behind. None of
        // these anchors wrap another link, so the first </a> closes each one.
        preg_match_all('/<a href="[^"]*\/news\/\d+" class="nws(?:lead|mini) reveal">.*?<\/a>/s', $html, $matches);
        $this->assertSame($articles->count(), count($matches[0]));
        $news = implode('', $matches[0]);
        $this->assertStringNotContainsString('<button', $news);
        $this->assertStringNotContainsString('btn btn-outline', $news);
    }

    public function test_homepage_events_are_a_schedule_rail_with_logistics_chips(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<ol class="elist">', $html);
        $this->assertStringContainsString('class="erow reveal"', $html);
        $this->assertStringContainsString('class="edate" datetime="', $html);
        $this->assertStringContainsString('class="edate-week"', $html);
        $this->assertStringContainsString('class="edate-day"', $html);
        $this->assertStringContainsString('class="edate-mon"', $html);
        $this->assertStringContainsString('class="echips"', $html);
        $this->assertStringContainsString('class="echip"', $html);
        $this->assertStringContainsString('class="erow-title"', $html);
        $this->assertStringContainsString('class="erow-desc"', $html);
        $this->assertStringContainsString('erow-go', $html);

        // The date used to sit in a small green pill below a large decorative thumbnail,
        // and every card carried its own "View Details" button.
        $this->assertStringNotContainsString('bi bi-calendar3', $html);

        preg_match('/<ol class="elist">.*?<\/ol>/s', $html, $matches);
        $list = $matches[0] ?? '';
        $this->assertNotSame('', $list, 'the events list should render');

        $rows = Event::published()->upcoming()->take(3)->get()->count();
        $this->assertSame($rows, substr_count($list, '<li>'), 'each event should be one list item');
        $this->assertSame($rows, preg_match_all('/<a href="[^"]+" class="erow reveal">/', $list));
        $this->assertStringNotContainsString('<button', $list);
        $this->assertStringNotContainsString('btn btn-outline', $list);

        // The decorative thumbnails are gone, so no empty grey box stands in for a photo.
        $this->assertStringNotContainsString('class="thumb"', $list);

        // Dates stay chronological (soonest first) and render as real <time> elements.
        $event = Event::published()->upcoming()->take(3)->first();
        $this->assertStringContainsString('datetime="'.$event->event_date->toDateString().'"', $list);
        $this->assertStringContainsString('>'.$event->event_date->format('d').'</span>', $list);
    }

    public function test_homepage_notices_are_a_dated_list_not_cards_with_buttons(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('class="sechead reveal"', $html);
        $this->assertStringContainsString('class="sechead-all"', $html);
        $this->assertStringContainsString('<ul class="nlist">', $html);
        $this->assertStringContainsString('class="nrow reveal"', $html);
        $this->assertStringContainsString('class="ndate" datetime="', $html);
        $this->assertStringContainsString('class="ndate-day"', $html);
        $this->assertStringContainsString('class="nrow-title"', $html);
        $this->assertStringContainsString('class="nrow-desc"', $html);
        $this->assertStringContainsString('nrow-go', $html);

        // Scope the rest to the list itself: the events and news sections below it keep
        // their own cards, so page-wide string checks would match the wrong markup.
        preg_match('/<ul class="nlist">.*?<\/ul>/s', $html, $matches);
        $list = $matches[0] ?? '';
        $this->assertNotSame('', $list, 'the notices list should render');

        $rows = Notice::published()->take(4)->get()->count();
        $this->assertSame($rows, substr_count($list, 'class="nrow reveal"'));
        $this->assertSame($rows, substr_count($list, '<li>'), 'each notice should be one list item');

        // Rows are whole-row links, so the old per-card "View Details" buttons are gone.
        $this->assertStringNotContainsString('<button', $list);
        $this->assertStringNotContainsString('btn btn-outline', $list);
        $this->assertSame($rows, preg_match_all('/<a href="[^"]+" class="nrow reveal">/', $list));

        // The dates are real <time> elements, so assistive tech reads the full date.
        $notice = Notice::published()->take(4)->first();
        $this->assertStringContainsString('datetime="'.$notice->published_at->toDateString().'"', $list);
        $this->assertStringContainsString('>'.$notice->published_at->format('d').'</span>', $list);
    }

    public function test_teacher_profile_uses_a_sticky_card_and_a_definition_list(): void
    {
        $teacher = Teacher::where('is_active', true)->firstOrFail();

        $html = $this->get('/teachers/'.$teacher->id)->assertOk()->getContent();

        // The page carried its own <style> block and a 900px breakpoint, and leaned on
        // !important overrides plus a magic 100px sticky offset.
        $view = file_get_contents(resource_path('views/frontend/teacher-show.blade.php'));
        $this->assertStringNotContainsString('<style', $view);
        $this->assertStringNotContainsString('teacher-grid', $view);
        $this->assertStringNotContainsString('900px', $view, 'breakpoints belong in the layout');
        $this->assertStringNotContainsString('width:130px;height:130px', $html);
        $this->assertStringNotContainsString('position:sticky;top:100px', $html);

        $this->assertStringContainsString('class="tprofile"', $html);
        $this->assertStringContainsString('class="tprofile-card"', $html);
        $this->assertStringContainsString('class="tprofile-role"', $html);
        $this->assertStringContainsString('<dl class="tprofile-meta">', $html);
        $this->assertStringContainsString('<dt>', $html);
        $this->assertStringContainsString('<dd>', $html);

        // The page header already prints the name as the <h1>, so it is not repeated as an
        // <h2> and the sections take <h2> instead of <h3>.
        $this->assertStringNotContainsString('<h2 style="font-size:1.3rem', $html);
        $this->assertStringContainsString('<h2>'.__('ui.teachers.about').'</h2>', $html);
        $this->assertStringContainsString('<h2>'.__('ui.teachers.subjects_taught').'</h2>', $html);
        $this->assertStringNotContainsString('<h3 style="margin-bottom', $html);

        // Subjects are a list of items, not cards nested inside a card.
        $this->assertStringContainsString('class="tpanel-head"', $html);

        $withSubjects = Teacher::where('is_active', true)->whereHas('subjects')->firstOrFail();
        $listed = $this->get('/teachers/'.$withSubjects->id)->assertOk()->getContent();
        $this->assertStringContainsString('<ul class="tsubjects">', $listed);
        $this->assertSame(
            $withSubjects->subjects->count(),
            substr_count($listed, 'class="tsubject"'),
            'every subject should render as one item',
        );

        $withoutSubjects = Teacher::where('is_active', true)->doesntHave('subjects')->first();
        if ($withoutSubjects) {
            $this->assertStringContainsString(
                'empty empty-sm',
                $this->get('/teachers/'.$withoutSubjects->id)->assertOk()->getContent(),
            );
        }
    }

    public function test_teacher_profile_email_and_phone_are_actionable_links(): void
    {
        $teacher = Teacher::where('is_active', true)->whereNotNull('email')->first();
        $this->assertNotNull($teacher, 'the seeded faculty should include an email address');

        $html = $this->get('/teachers/'.$teacher->id)->assertOk()->getContent();

        $this->assertStringContainsString('class="tprofile-actions"', $html);
        $this->assertStringContainsString('href="mailto:'.$teacher->email.'"', $html);

        if ($teacher->phone) {
            $this->assertStringContainsString('href="tel:'.$teacher->phone.'"', $html);
        }

        // Contact details moved into the action buttons, so they must not be listed twice.
        $this->assertStringNotContainsString('<dd>'.$teacher->email.'</dd>', $html);
    }

    public function test_teachers_directory_uses_cards_with_a_shared_filter_bar(): void
    {
        $html = $this->get('/teachers')->assertOk()->getContent();

        // The old filter form and cards carried their own inline grid and avatar sizes.
        $this->assertStringNotContainsString('grid-template-columns:2fr 1.4fr auto', $html);
        $this->assertStringNotContainsString('width:84px;height:84px', $html);

        $this->assertStringContainsString('class="tbar reveal"', $html);
        $this->assertStringContainsString('class="row tbar-actions"', $html);
        $this->assertStringContainsString('class="tbar-count"', $html);
        $this->assertStringContainsString('class="tcard reveal"', $html);
        $this->assertStringContainsString('class="tcard-media"', $html);
        $this->assertStringContainsString('class="tcard-go"', $html);

        // Each card is one whole-card link, and the heading no longer skips from h1 to h4.
        $this->assertStringContainsString('<h2>', $html);
        $this->assertStringNotContainsString('<h4 style="font-size:1rem', $html);
        // Walk every page so the assertion holds whatever per-page size the controller uses.
        $listed = 0;
        for ($page = 1; $page <= 20; $page++) {
            $cards = substr_count($this->get('/teachers?page=' . $page)->assertOk()->getContent(), 'class="tcard reveal"');

            if ($cards === 0) {
                break;
            }

            $listed += $cards;
        }

        $this->assertSame(
            $this->activeTeacherCount(),
            $listed,
            'every active teacher should be listed exactly once',
        );
    }

    public function test_public_pagination_uses_the_shared_pg_markup(): void
    {
        // AppServiceProvider points the paginator at partials.pagination, so all six paginated
        // public pages share the .pg styling in the layout rather than Laravel's Tailwind view.
        foreach (range(1, 5) as $i) {
            Teacher::create([
                'name' => 'Pagination Teacher ' . $i,
                'designation' => 'Lecturer',
                'department' => 'Science',
                'is_active' => true,
            ]);
        }

        $html = $this->get('/teachers')->assertOk()->getContent();

        $this->assertStringContainsString('<nav class="pg" role="navigation"', $html);
        $this->assertStringContainsString('class="pg-item pg-active" aria-current="page"', $html);
        $this->assertStringContainsString('rel="next"', $html);
        // on the first page there is nowhere back to go, so previous is inert rather than a link
        $this->assertStringContainsString('class="pg-item pg-disabled" aria-disabled="true"', $html);
        $this->assertStringNotContainsString('rel="prev"', $html);

        $this->assertStringContainsString(
            'rel="prev"',
            $this->get('/teachers?page=2')->assertOk()->getContent(),
        );
        $this->assertStringNotContainsString('relative inline-flex', $html);
    }

    private function activeTeacherCount(): int
    {
        return Teacher::where('is_active', true)->count();
    }

    public function test_homepage_faculty_is_a_lead_profile_beside_compact_rows(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // Four identical centred cards with a button each read as a roster, and stacked on
        // phones they ran to roughly 1300px of height.
        $this->assertStringNotContainsString('width:88px;height:88px', $html);
        $this->assertStringNotContainsString('btn-outline btn-sm" style="margin-top:14px', $html);

        $this->assertStringContainsString('class="faculty ', $html);
        $this->assertStringContainsString('class="faculty-lead reveal"', $html);
        $this->assertStringContainsString('class="faculty-lead-photo"', $html);
        $this->assertStringContainsString('class="dept-chip"', $html);
        $this->assertStringContainsString('class="faculty-go"', $html);
        $this->assertStringContainsString('class="faculty-list"', $html);
        $this->assertStringContainsString('class="faculty-row reveal"', $html);
        $this->assertStringContainsString('faculty-row-go', $html);

        // Every profile is a single whole-card link, plus one link out to the full faculty.
        $this->assertSame(
            $this->featuredTeacherLinks(),
            substr_count($html, 'class="faculty-lead reveal"') + substr_count($html, 'class="faculty-row reveal"'),
            'each featured teacher should render exactly one card link',
        );
        $this->assertStringContainsString('href="'.route('teachers').'" class="prog-all"', $html);
        $this->assertStringContainsString(__('ui.home.faculty_all'), $html);
    }

    /** Mirrors the featured-teacher query the homepage controller uses. */
    private function featuredTeacherLinks(): int
    {
        return Teacher::where('is_featured', true)->where('is_active', true)->take(4)->count();
    }

    public function test_principal_page_is_a_profile_column_beside_the_letter(): void
    {
        $html = $this->get('/principal')->assertOk()->getContent();

        // The old centred profile card stacked a full-width gradient banner over the letter,
        // and the page carried its own <style> block instead of using the shared layout.
        $view = file_get_contents(resource_path('views/frontend/principal.blade.php'));
        $this->assertStringNotContainsString('principal-card', $view);
        $this->assertStringNotContainsString('<style', $view);
        $this->assertStringNotContainsString('780px', $view, 'breakpoints belong in the layout');

        $this->assertStringContainsString('class="principal-page"', $html);
        $this->assertStringContainsString('class="principal-side-inner"', $html);
        $this->assertStringContainsString('class="principal-letter reveal"', $html);
        $this->assertStringContainsString('class="principal-signoff"', $html);

        // page-head already prints the <h1>, so the profile must not skip to an <h3>.
        $this->assertStringContainsString('<h2 class="principal-name">', $html);
        $this->assertStringNotContainsString('<h3 style="font-size:1.15rem', $html);

        // Email is a real mailto link, and the value cards got their own heading.
        $this->assertStringContainsString('href="mailto:', $html);
        $this->assertStringContainsString('What guides our school', $html);
        $this->assertSame(
            3,
            substr_count($html, 'class="tile reveal"'),
            'the three value cards should render as tiles',
        );
    }

    public function test_classes_page_uses_a_disclosure_list_with_the_first_class_open(): void
    {
        $html = $this->get('/classes')->assertOk()->getContent();

        // Ten seeded classes, each a native <details> so the page needs no JS.
        $this->assertSame(10, substr_count($html, '<details class="cls reveal"'));
        $this->assertSame(10, substr_count($html, 'class="cls-head"'));
        $this->assertSame(10, substr_count($html, 'class="cls-initial"'));
        $this->assertSame(10, substr_count($html, 'bi-chevron-down cls-caret"'));

        // Only the first class starts expanded.
        $this->assertSame(1, substr_count($html, '<details class="cls reveal" open>'));

        // Counts replace the old always-visible badge walls.
        $this->assertSame(10, substr_count($html, '2 sections · 8 subjects'));

        $this->assertStringContainsString('cls-hint', $html);
        $this->assertStringContainsString('cls-chip-subject', $html);
    }

    public function test_homepage_program_cards_are_whole_links_with_one_shared_cta(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // The homepage previews 6 of the 10 seeded classes.
        $this->assertSame(6, substr_count($html, 'class="prog reveal"'));
        $this->assertSame(6, substr_count($html, 'class="prog-num" aria-hidden="true">'));
        $this->assertSame(6, substr_count($html, 'class="prog-meta"'));
        $this->assertSame(6, substr_count($html, 'class="prog-go"'));
        $this->assertStringContainsString('balanced curriculum', $html);

        // Six whole-card links plus one shared CTA, all pointing at the classes page.
        // Scoped by class so the footer link to the same page is not counted.
        $classesUrl = preg_quote(url('/classes'), '/');
        $this->assertSame(1, preg_match_all('/<a href="'.$classesUrl.'" class="prog-all reveal">/', $html));
        $this->assertSame(6, preg_match_all('/<a href="'.$classesUrl.'" class="prog reveal">/', $html));
    }

    public function test_homepage_program_grid_is_a_keyboard_reachable_scroller_on_phones(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // tabindex keeps the horizontal region scrollable without a pointer.
        $this->assertStringContainsString('class="grid grid-3 prog-grid" tabindex="0" role="group"', $html);

        $layout = file_get_contents(resource_path('views/frontend/layouts/app.blade.php'));
        $mobile = substr($layout, strpos($layout, '@media (max-width: 720px)'));

        $this->assertStringContainsString('scroll-snap-type: x mandatory;', $mobile);
        $this->assertStringContainsString('overflow-x: auto;', $mobile);
        $this->assertStringContainsString('.prog { flex: 0 0 78%; scroll-snap-align: start; }', $mobile);
    }

    public function test_homepage_glance_band_replaces_the_flat_stat_cards(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('class="glance reveal"', $html);
        $this->assertStringContainsString('glance-stats', $html);
        $this->assertSame(4, substr_count($html, 'class="glance-stat"'));
        $this->assertSame(4, substr_count($html, 'class="glance-value"'));
        $this->assertSame(3, substr_count($html, 'class="glance-suffix">+</span>'));

        // A link into the about page gives the band a purpose beyond the numbers.
        $this->assertStringContainsString('glance-link', $html);
        $this->assertStringContainsString(url('/about'), $html);

        // The old markup used inline styles on four separate cards.
        $this->assertStringNotContainsString('text-align:center;padding:34px 22px;', $html);
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
