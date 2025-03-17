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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->integer('total_score')->default(0)->after('bio');
            $table->integer('games_played')->default(0)->after('total_score');
            $table->enum('online_status', ['online', 'offline', 'away'])->default('offline')->after('games_played');
            $table->timestamp('last_online_at')->nullable()->after('online_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'avatar',
                'bio',
                'total_score',
                'games_played',
                'online_status',
                'last_online_at'
            ]);
        });
    }
};
