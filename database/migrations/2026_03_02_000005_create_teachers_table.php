<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('qualification')->nullable();
            $table->string('experience')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->date('join_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
