<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('course_code');
            $table->string('course_title');
            $table->string('semester_year');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('course_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('main_topic')->nullable();
            $table->text('topic_outcomes')->nullable();
            $table->integer('wks')->nullable();
            $table->boolean('is_midterm')->default(false);  // New column for midterm examination
            $table->boolean('is_final')->default(false);    // New column for final examination
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_chapters');
        Schema::dropIfExists('courses');
    }
}

