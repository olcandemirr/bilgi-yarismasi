<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_quizzes', function (Blueprint $table) {
            $table->longText('custom_questions')->nullable()->after('template_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_quizzes', function (Blueprint $table) {
            $table->dropColumn('custom_questions');
        });
    }
};
