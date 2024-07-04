<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseInformationTable extends Migration
{
    public function up()
    {
        Schema::create('course_information', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 10);
            $table->string('course_title', 255);
            $table->string('semester_year', 50);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_information');
    }
}