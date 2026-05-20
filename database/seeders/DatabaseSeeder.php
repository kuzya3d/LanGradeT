<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LearningContentSeeder::class,
            ExpandedLearningSeeder::class,
            RichLessonsSeeder::class,
            SourceInspiredContentSeeder::class,
            GrammarConceptsSeeder::class,
            VocabularySeeder::class,
            SentenceCorpusSeeder::class,
        ]);
    }
}
