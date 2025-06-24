<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Models\Word;

class CollectionWordSeeder extends Seeder
{
    public function run()
    {
        $mapping = [
            'Фрукты' => ['apple', 'banana', 'grape', 'orange', 'pear', 'peach', 'plum', 'lemon', 'kiwi', 'pineapple'],
            'Овощи' => ['carrot', 'potato', 'tomato', 'cucumber', 'pepper', 'onion', 'garlic', 'broccoli', 'spinach', 'cabbage'],
            'Животные' => ['dog', 'cat', 'cow', 'horse', 'sheep', 'pig', 'chicken', 'duck', 'rabbit', 'goat'],
            'Одежда' => ['shirt', 'pants', 'jacket', 'dress', 'skirt', 'shoes', 'boots', 'socks', 'hat', 'scarf'],
            'Еда' => ['bread', 'milk', 'cheese', 'egg', 'butter', 'meat', 'fish', 'soup', 'salad', 'rice'],
            'Природа' => ['tree', 'flower', 'mountain', 'river', 'lake', 'sky', 'cloud', 'sun', 'rain', 'snow'],
            'Цвета' => ['red', 'blue', 'green', 'yellow', 'black', 'white', 'brown', 'gray', 'pink', 'purple'],
            'Профессии' => ['teacher', 'doctor', 'policeman', 'driver', 'nurse', 'chef', 'engineer', 'builder', 'fireman', 'artist'],
        ];

        foreach ($mapping as $collectionTitle => $wordList) {
            $collection = Collection::where('title', $collectionTitle)->first();

            if ($collection) {
                $wordIds = Word::whereIn('english', $wordList)->pluck('id');
                $collection->words()->sync($wordIds);
            }
        }
    }
}
