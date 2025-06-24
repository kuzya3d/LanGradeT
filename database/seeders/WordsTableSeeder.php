<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Word;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        $words = [
            // Фрукты
            ['english' => 'apple', 'russian' => 'яблоко', 'image' => 'apple.jpg'],
            ['english' => 'banana', 'russian' => 'банан', 'image' => 'banana.jpg'],
            ['english' => 'grape', 'russian' => 'виноград', 'image' => 'grape.jpg'],
            ['english' => 'orange', 'russian' => 'апельсин', 'image' => 'orange.jpg'],
            ['english' => 'pear', 'russian' => 'груша', 'image' => 'pear.jpg'],
            ['english' => 'peach', 'russian' => 'персик', 'image' => 'peach.jpg'],
            ['english' => 'plum', 'russian' => 'слива', 'image' => 'plum.jpg'],
            ['english' => 'lemon', 'russian' => 'лимон', 'image' => 'lemon.jpg'],
            ['english' => 'kiwi', 'russian' => 'киви', 'image' => 'kiwi.jpg'],
            ['english' => 'pineapple', 'russian' => 'ананас', 'image' => 'pineapple.jpg'],

            // Овощи
            ['english' => 'carrot', 'russian' => 'морковь', 'image' => 'carrot.jpg'],
            ['english' => 'potato', 'russian' => 'картофель', 'image' => 'potato.jpg'],
            ['english' => 'tomato', 'russian' => 'помидор', 'image' => 'tomato.jpg'],
            ['english' => 'cucumber', 'russian' => 'огурец', 'image' => 'cucumber.jpg'],
            ['english' => 'pepper', 'russian' => 'перец', 'image' => 'pepper.jpg'],
            ['english' => 'onion', 'russian' => 'лук', 'image' => 'onion.jpg'],
            ['english' => 'garlic', 'russian' => 'чеснок', 'image' => 'garlic.jpg'],
            ['english' => 'broccoli', 'russian' => 'брокколи', 'image' => 'broccoli.jpg'],
            ['english' => 'spinach', 'russian' => 'шпинат', 'image' => 'spinach.jpg'],
            ['english' => 'cabbage', 'russian' => 'капуста', 'image' => 'cabbage.jpg'],

            // Животные
            ['english' => 'dog', 'russian' => 'собака', 'image' => 'dog.jpg'],
            ['english' => 'cat', 'russian' => 'кошка', 'image' => 'cat.jpg'],
            ['english' => 'cow', 'russian' => 'корова', 'image' => 'cow.jpg'],
            ['english' => 'horse', 'russian' => 'лошадь', 'image' => 'horse.jpg'],
            ['english' => 'sheep', 'russian' => 'овца', 'image' => 'sheep.jpg'],
            ['english' => 'pig', 'russian' => 'свинья', 'image' => 'pig.jpg'],
            ['english' => 'chicken', 'russian' => 'курица', 'image' => 'chicken.jpg'],
            ['english' => 'duck', 'russian' => 'утка', 'image' => 'duck.jpg'],
            ['english' => 'rabbit', 'russian' => 'кролик', 'image' => 'rabbit.jpg'],
            ['english' => 'goat', 'russian' => 'коза', 'image' => 'goat.jpg'],

            // Одежда
            ['english' => 'shirt', 'russian' => 'рубашка', 'image' => 'shirt.jpg'],
            ['english' => 'pants', 'russian' => 'брюки', 'image' => 'pants.jpg'],
            ['english' => 'jacket', 'russian' => 'куртка', 'image' => 'jacket.jpg'],
            ['english' => 'dress', 'russian' => 'платье', 'image' => 'dress.jpg'],
            ['english' => 'skirt', 'russian' => 'юбка', 'image' => 'skirt.jpg'],
            ['english' => 'shoes', 'russian' => 'обувь', 'image' => 'shoes.jpg'],
            ['english' => 'boots', 'russian' => 'сапоги', 'image' => 'boots.jpg'],
            ['english' => 'socks', 'russian' => 'носки', 'image' => 'socks.jpg'],
            ['english' => 'hat', 'russian' => 'шляпа', 'image' => 'hat.jpg'],
            ['english' => 'scarf', 'russian' => 'шарф', 'image' => 'scarf.jpg'],

            // Еда
            ['english' => 'bread', 'russian' => 'хлеб', 'image' => 'bread.jpg'],
            ['english' => 'milk', 'russian' => 'молоко', 'image' => 'milk.jpg'],
            ['english' => 'cheese', 'russian' => 'сыр', 'image' => 'cheese.jpg'],
            ['english' => 'egg', 'russian' => 'яйцо', 'image' => 'egg.jpg'],
            ['english' => 'butter', 'russian' => 'масло', 'image' => 'butter.jpg'],
            ['english' => 'meat', 'russian' => 'мясо', 'image' => 'meat.jpg'],
            ['english' => 'fish', 'russian' => 'рыба', 'image' => 'fish.jpg'],
            ['english' => 'soup', 'russian' => 'суп', 'image' => 'soup.jpg'],
            ['english' => 'salad', 'russian' => 'салат', 'image' => 'salad.jpg'],
            ['english' => 'rice', 'russian' => 'рис', 'image' => 'rice.jpg'],

            // Природа
            ['english' => 'tree', 'russian' => 'дерево', 'image' => 'tree.jpg'],
            ['english' => 'flower', 'russian' => 'цветок', 'image' => 'flower.jpg'],
            ['english' => 'mountain', 'russian' => 'гора', 'image' => 'mountain.jpg'],
            ['english' => 'river', 'russian' => 'река', 'image' => 'river.jpg'],
            ['english' => 'lake', 'russian' => 'озеро', 'image' => 'lake.jpg'],
            ['english' => 'sky', 'russian' => 'небо', 'image' => 'sky.jpg'],
            ['english' => 'cloud', 'russian' => 'облако', 'image' => 'cloud.jpg'],
            ['english' => 'sun', 'russian' => 'солнце', 'image' => 'sun.jpg'],
            ['english' => 'rain', 'russian' => 'дождь', 'image' => 'rain.jpg'],
            ['english' => 'snow', 'russian' => 'снег', 'image' => 'snow.jpg'],

            // Цвета
            ['english' => 'red', 'russian' => 'красный', 'image' => 'red.jpg'],
            ['english' => 'blue', 'russian' => 'синий', 'image' => 'blue.jpg'],
            ['english' => 'green', 'russian' => 'зелёный', 'image' => 'green.jpg'],
            ['english' => 'yellow', 'russian' => 'жёлтый', 'image' => 'yellow.jpg'],
            ['english' => 'black', 'russian' => 'чёрный', 'image' => 'black.jpg'],
            ['english' => 'white', 'russian' => 'белый', 'image' => 'white.jpg'],
            ['english' => 'brown', 'russian' => 'коричневый', 'image' => 'brown.jpg'],
            ['english' => 'pink', 'russian' => 'розовый', 'image' => 'pink.jpg'],
            ['english' => 'gray', 'russian' => 'серый', 'image' => 'gray.jpg'],
            ['english' => 'purple', 'russian' => 'фиолетовый', 'image' => 'purple.jpg'],

            // Профессии
            ['english' => 'teacher', 'russian' => 'учитель', 'image' => 'teacher.jpg'],
            ['english' => 'doctor', 'russian' => 'врач', 'image' => 'doctor.jpg'],
            ['english' => 'policeman', 'russian' => 'полицейский', 'image' => 'policeman.jpg'],
            ['english' => 'driver', 'russian' => 'водитель', 'image' => 'driver.jpg'],
            ['english' => 'nurse', 'russian' => 'медсестра', 'image' => 'nurse.jpg'],
            ['english' => 'chef', 'russian' => 'повар', 'image' => 'chef.jpg'],
            ['english' => 'engineer', 'russian' => 'инженер', 'image' => 'engineer.jpg'],
            ['english' => 'builder', 'russian' => 'строитель', 'image' => 'builder.jpg'],
            ['english' => 'fireman', 'russian' => 'пожарный', 'image' => 'fireman.jpg'],
            ['english' => 'artist', 'russian' => 'художник', 'image' => 'artist.jpg'],
        ];

        foreach ($words as $word) {
            Word::create($word);
        }
    }
}
