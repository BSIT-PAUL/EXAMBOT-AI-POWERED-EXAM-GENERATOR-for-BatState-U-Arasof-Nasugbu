<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->unsignedBigInteger('tos_id')->nullable()->after('id');
            $table->foreign('tos_id')->references('id')->on('table_of_specifications')->onDelete('cascade');

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->dropForeign(['tos_id']);
            $table->dropColumn('tos_id');
        });
    }
};
