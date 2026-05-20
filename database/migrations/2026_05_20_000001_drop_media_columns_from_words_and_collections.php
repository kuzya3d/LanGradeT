<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('words', function (Blueprint $table) {
            if (Schema::hasColumn('words', 'image')) {
                $table->dropColumn('image');
            }

            if (Schema::hasColumn('words', 'audio_path')) {
                $table->dropColumn('audio_path');
            }
        });

        Schema::table('collections', function (Blueprint $table) {
            if (Schema::hasColumn('collections', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('words', function (Blueprint $table) {
            if (! Schema::hasColumn('words', 'image')) {
                $table->string('image')->nullable()->after('example_ru');
            }

            if (! Schema::hasColumn('words', 'audio_path')) {
                $table->string('audio_path')->nullable()->after('image');
            }
        });

        Schema::table('collections', function (Blueprint $table) {
            if (! Schema::hasColumn('collections', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }
};
