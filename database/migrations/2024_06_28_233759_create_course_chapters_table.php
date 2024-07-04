<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseChaptersTable extends Migration
{
    public function up()
    {
        Schema::create('course_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_information_id');
            $table->integer('main_topic_number');
            $table->string('main_topic', 255);
            $table->text('topic_outcomes');
            $table->timestamps();

            $table->foreign('course_information_id')->references('id')->on('course_information')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_chapters');
    }
}