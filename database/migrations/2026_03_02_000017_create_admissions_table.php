<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->string('student_name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('applying_class');
            $table->string('previous_school')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->text('additional_info')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
