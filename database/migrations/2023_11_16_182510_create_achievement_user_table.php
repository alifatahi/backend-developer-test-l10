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
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('achievement_id')->constrained();
            // the "level" field defines that which level of each achievement has riched
            $table->integer('level');
            // a title for this achievement
            $table->string('title');
            // the "is_last" field defines that this achievement is the last achievement of a user in its similar achievements
            // old similar achievements has been kept to save the history
            $table->boolean('is_last')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_user');
    }
};
