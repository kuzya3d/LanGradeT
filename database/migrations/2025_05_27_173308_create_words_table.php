<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('english')->unique();
            $table->string('russian');
            $table->string('transcription')->nullable();
            $table->string('part_of_speech')->nullable();
            $table->string('difficulty', 10)->default('A1');
            $table->text('example_en')->nullable();
            $table->text('example_ru')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
