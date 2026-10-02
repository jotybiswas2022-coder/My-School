<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bengali translations for user-entered content.
     * When a *_bn column is empty the English value is used as a fallback.
     *
     * @var array<string, array<int, string>>
     */
    private array $columns = [
        'classes' => ['name_bn', 'description_bn'],
        'subjects' => ['name_bn', 'description_bn'],
        'teachers' => ['name_bn', 'designation_bn', 'department_bn', 'qualification_bn', 'bio_bn'],
        'notices' => ['title_bn', 'description_bn'],
        'events' => ['title_bn', 'description_bn', 'location_bn'],
        'news' => ['title_bn', 'description_bn'],
        'gallery_albums' => ['title_bn', 'description_bn'],
    ];

    public function up(): void
    {
        foreach ($this->columns as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column) {
                    $blueprint->text($column)->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropColumn($columns);
            });
        }
    }
};
