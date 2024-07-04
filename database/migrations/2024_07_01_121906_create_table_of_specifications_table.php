<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOfSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_of_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('examination_type', 100);
            $table->string('course_code', 100);
            $table->string('topic', 255);
            $table->integer('no_of_hours_a');
            $table->decimal('no_of_hours_b',5, 2);
            $table->decimal('weight', 5, 2);
            $table->integer('remembering');
            $table->decimal('remembering_percentage', 5, 2);
            $table->integer('understanding');
            $table->decimal('understanding_percentage', 5, 2);
            $table->integer('applying');
            $table->decimal('applying_percentage', 5, 2);
            $table->integer('analyzing');
            $table->decimal('analyzing_percentage', 5, 2);
            $table->integer('evaluating');
            $table->decimal('evaluating_percentage', 5, 2);
            $table->integer('creating');
            $table->decimal('creating_percentage', 5, 2);
            $table->integer('total_no_of_points');
            $table->integer('overall_points');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_code')->references('course_code')->on('course_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_of_specifications');
    }
}
