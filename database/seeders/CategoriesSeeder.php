<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['species' => 'Akcja'],
            ['species' => 'Komedia'],
            ['species' => 'Dramat'],
            ['species' => 'Fantasy'],
            ['species' => 'Horror'],
            ['species' => 'Romans'],
            ['species' => 'Przygodowy'],
            ['species' => 'Thriller'],
            ['species' => 'Naukowa-fikcja'],
            ['species' => 'Dokumentalny'],
            ['species' => 'Animacja'],
            ['species' => 'Biograficzny'],
            ['species' => 'Historyczny'],
            ['species' => 'Muzyczny'],
            ['species' => 'Wojenny'],
            ['species' => 'Western'],
            ['species' => 'Sportowy'],
            ['species' => 'Kryminał'],
            ['species' => 'Mystery'],
            ['species' => 'Rodzinny']
        ]);
    }
}
