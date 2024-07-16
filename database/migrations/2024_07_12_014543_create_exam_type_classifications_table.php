<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamTypeClassificationsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_type_classifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('topic');
            $table->string('blooms_level');
            $table->string('exam_type');
            $table->text('topic_outcomes');  // Add this line
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_type_classifications');
    }
}
