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
        Schema::create('game_invites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('game_id')->nullable(); // Oluşturulan oyun için benzersiz ID
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->json('categories')->nullable(); // Seçilen kategori ID'leri
            $table->json('game_config')->nullable(); // Oyun ayarları
            $table->dateTime('expires_at'); // Davetin sona erme zamanı
            $table->timestamps();
            
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_invites');
    }
};
