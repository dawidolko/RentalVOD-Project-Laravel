<?php

namespace Database\Seeders;

use App\Models\Opinion;
use Illuminate\Database\Seeder;

class OpinionsSeeder extends Seeder
{
    public function run()
    {
        Opinion::truncate();
        $opinions = [
            [
                'user_id' => 1,
                'movie_id' => 1,
                'content' => 'Bardzo dobry film, świetna obsada i fabuła.',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => 2,
                'movie_id' => 2,
                'content' => 'Nie podobał mi się ten film, zbyt przewidywalny.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => 3,
                'movie_id' => 3,
                'content' => 'Film wart polecenia, świetna gra aktorska.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => 4,
                'movie_id' => 4,
                'content' => 'Film zaskoczył mnie, polecam.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => 5,
                'movie_id' => 5,
                'content' => 'Film zbyt długi, nie polecam.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => 6,
                'movie_id' => 6,
                'content' => 'Film wart uwagi, polecam.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'movie_id' => 8,
                'content' => 'Film wart uwagi, polecam.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'movie_id' => 8,
                'content' => 'Wspaniały film, bardzo poruszający.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'movie_id' => 9,
                'content' => 'Film pełen akcji, świetnie się ogląda.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'movie_id' => 10,
                'content' => 'Bardzo śmieszny film, świetne efekty specjalne.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11,
                'movie_id' => 11,
                'content' => 'Film o głębokiej treści, polecam każdemu.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12,
                'movie_id' => 12,
                'content' => 'Niezwykle poruszający film, wart obejrzenia.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 13,
                'movie_id' => 13,
                'content' => 'Film zaskoczył mnie, nie spodziewałem się takiej fabuły.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 14,
                'movie_id' => 14,
                'content' => 'Bardzo interesujący film, polecam fanom gatunku.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 15,
                'movie_id' => 15,
                'content' => 'Film miał świetny klimat, ale trochę przewidywalny.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 16,
                'movie_id' => 16,
                'content' => 'Wart uwagi film, świetne zdjęcia.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 17,
                'movie_id' => 17,
                'content' => 'Film momentami wzruszający, chociaż trochę długi.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 18,
                'movie_id' => 18,
                'content' => 'Nie spodobał mi się ten film, fabuła zbyt banalna.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 19,
                'movie_id' => 19,
                'content' => 'Film pełen napięcia, świetny thriller.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 20,
                'movie_id' => 20,
                'content' => 'Film niezwykle interesujący, wart uwagi.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'movie_id' => 21,
                'content' => 'Zaskakujący film, wart uwagi, świetnie zagrane role.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'movie_id' => 22,
                'content' => 'Film poruszający trudne tematy społeczne, świetna gra aktorska.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'movie_id' => 23,
                'content' => 'Doskonały thriller, trzymał w napięciu do ostatnich chwil.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'movie_id' => 24,
                'content' => 'Film wart obejrzenia, chociaż momentami przewidywalny.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'movie_id' => 25,
                'content' => 'Świetny humor, film na poprawę nastroju.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'movie_id' => 26,
                'content' => 'Film o miłości, wzruszająca historia, świetna reżyseria.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'movie_id' => 27,
                'content' => 'Film, który skłania do refleksji, przemyślenia własnych wyborów.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'movie_id' => 28,
                'content' => 'Niebywałe widowisko, spektakularna realizacja.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'movie_id' => 29,
                'content' => 'Film wart uwagi, zaskakujący finał.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'movie_id' => 30,
                'content' => 'Doskonała rozrywka, świetne efekty specjalne.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'movie_id' => 31,
                'content' => 'Film poruszający tematykę społeczną, bardzo wzruszający.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'movie_id' => 32,
                'content' => 'Zaskakujący film, świetne aktorstwo, niezapomniana historia.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'movie_id' => 33,
                'content' => 'Film wart polecenia, wciągająca fabuła.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'movie_id' => 34,
                'content' => 'Film zaskakujący, nieoczekiwane zwroty akcji.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'movie_id' => 35,
                'content' => 'Ciekawy film, chociaż momentami lekko przewidywalny.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'movie_id' => 36,
                'content' => 'Film wart uwagi, niezwykła atmosfera.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'movie_id' => 37,
                'content' => 'Film zaskakujący, nieprzewidywalny, trzymający w napięciu.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'movie_id' => 38,
                'content' => 'Niezwykle poruszający film, świetnie zagrane role.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'movie_id' => 39,
                'content' => 'Film, który pozostaje w pamięci, warto obejrzeć.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'movie_id' => 40,
                'content' => 'Film wart uwagi, niecodzienna historia, znakomita reżyseria.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11,
                'movie_id' => 41,
                'content' => 'Film ciekawy, chociaż momentami przewidywalny.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12,
                'movie_id' => 42,
                'content' => 'Film wart uwagi, niezwykła atmosfera.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 13,
                'movie_id' => 43,
                'content' => 'Film, który skłania do refleksji, warto obejrzeć.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 14,
                'movie_id' => 44,
                'content' => 'Film wart uwagi, znakomita gra aktorska.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 15,
                'movie_id' => 45,
                'content' => 'Zaskakujący film, trzymający w napięciu do końca.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 16,
                'movie_id' => 46,
                'content' => 'Film wart uwagi, znakomite efekty specjalne.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 17,
                'movie_id' => 47,
                'content' => 'Film wart uwagi, poruszająca historia.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 18,
                'movie_id' => 48,
                'content' => 'Doskonała rozrywka, świetne efekty specjalne.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 19,
                'movie_id' => 49,
                'content' => 'Film poruszający tematykę społeczną, bardzo ważny przekaz.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => 20,
                'movie_id' => 50,
                'content' => 'Film wart uwagi, ciekawa fabuła.',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],                                    
        ];

        foreach ($opinions as $opinion) {
            Opinion::insert([
                'user_id' => $opinion['user_id'],
                'movie_id' => $opinion['movie_id'],
                'content' => $opinion['content'],
                'created_at' => $opinion['created_at'],
                'updated_at' => $opinion['updated_at'],
            ]);
        }
    }
}
