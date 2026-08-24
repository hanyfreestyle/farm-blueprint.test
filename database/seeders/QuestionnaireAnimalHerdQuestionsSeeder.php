<?php

namespace Database\Seeders;

use Database\Seeders\Questions\AnimalHerd\AnimalIdentityQuestionsSeeder;
use Database\Seeders\Questions\AnimalHerd\AnimalSourceQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireAnimalHerdQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AnimalIdentityQuestionsSeeder::class,
            AnimalSourceQuestionsSeeder::class,
        ]);
    }
}
