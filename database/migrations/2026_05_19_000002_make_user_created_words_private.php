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

        DB::table('user_words as uw')
            ->leftJoin('collection_word as cw', 'cw.word_id', '=', 'uw.word_id')
            ->whereNull('cw.word_id')
            ->groupBy('uw.word_id')
            ->selectRaw('uw.word_id, MIN(uw.user_id) as owner_id, COUNT(DISTINCT uw.user_id) as users_count')
            ->orderBy('uw.word_id')
            ->lazy()
            ->each(function (object $privateWord): void {
                if ((int) $privateWord->users_count !== 1) {
                    return;
                }

                DB::table('words')
                    ->where('id', $privateWord->word_id)
                    ->update(['user_id' => $privateWord->owner_id]);
            });

        DB::table('words')
            ->where('russian', 'like', '%/%')
            ->select(['id', 'russian'])
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $word): void {
                $russian = str_replace([' / ', '/', '  '], [', ', ', ', ' '], $word->russian);
                $russian = trim($russian, ', ');

                DB::table('words')
                    ->where('id', $word->id)
                    ->update(['russian' => $russian]);
            });

        DB::table('words')
            ->where('english', 'i am head over heels for you')
            ->where('russian', 'not like', '%без ума от тебя%')
            ->select(['id', 'russian'])
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $word): void {
                DB::table('words')
                    ->where('id', $word->id)
                    ->update(['russian' => $word->russian . ', я без ума от тебя']);
            });
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
