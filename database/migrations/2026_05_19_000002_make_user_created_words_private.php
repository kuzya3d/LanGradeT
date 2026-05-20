<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('words', function (Blueprint $table) {
            $table->dropUnique('words_english_unique');
            $table->index(['user_id', 'english']);
        });

        DB::statement("
            UPDATE words w
            JOIN (
                SELECT uw.word_id, MIN(uw.user_id) AS owner_id, COUNT(DISTINCT uw.user_id) AS users_count
                FROM user_words uw
                LEFT JOIN collection_word cw ON cw.word_id = uw.word_id
                WHERE cw.word_id IS NULL
                GROUP BY uw.word_id
                HAVING users_count = 1
            ) private_words ON private_words.word_id = w.id
            SET w.user_id = private_words.owner_id
        ");

        DB::table('words')
            ->where('russian', 'like', '%/%')
            ->update(['russian' => DB::raw("TRIM(BOTH ', ' FROM REPLACE(REPLACE(REPLACE(russian, ' / ', ', '), '/', ', '), '  ', ' '))")]);

        DB::table('words')
            ->where('english', 'i am head over heels for you')
            ->where('russian', 'not like', '%без ума от тебя%')
            ->update(['russian' => DB::raw("CONCAT(russian, ', я без ума от тебя')")]);
    }

    public function down(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'english']);
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('words', function (Blueprint $table) {
            $table->unique('english');
        });
    }
};
