<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionsTableSeeder extends Seeder
{
    public function run()
    {
        $collections = [
            ['title' => 'Фрукты', 'description' => 'Слова по теме "Фрукты"', 'image' => 'fruits.jpg'],
            ['title' => 'Овощи', 'description' => 'Слова по теме "Овощи"', 'image' => 'vegetables.jpg'],
            ['title' => 'Животные', 'description' => 'Слова по теме "Животные"', 'image' => 'animals.jpg'],
            ['title' => 'Одежда', 'description' => 'Слова по теме "Одежда"', 'image' => 'clothes.jpg'],
            ['title' => 'Еда', 'description' => 'Слова по теме "Еда"', 'image' => 'food.jpg'],
            ['title' => 'Природа', 'description' => 'Слова по теме "Природа"', 'image' => 'nature.jpg'],
            ['title' => 'Цвета', 'description' => 'Слова по теме "Цвета"', 'image' => 'colors.jpg'],
            ['title' => 'Профессии', 'description' => 'Слова по теме "Профессии"', 'image' => 'jobs.jpg'],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
