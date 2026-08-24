<?php

namespace Database\Seeders;

use Database\Seeders\Questions\AnimalHerd\AnimalIdentityQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireAnimalHerdQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AnimalIdentityQuestionsSeeder::class,
        ]);
    }
}
