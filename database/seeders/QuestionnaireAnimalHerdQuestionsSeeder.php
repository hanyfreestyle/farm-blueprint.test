<?php

namespace Database\Seeders;

use Database\Seeders\Questions\AnimalHerd\AnimalIdentityQuestionsSeeder;
use Database\Seeders\Questions\AnimalHerd\AnimalPedigreeQuestionsSeeder;
use Database\Seeders\Questions\AnimalHerd\AnimalSourceQuestionsSeeder;
use Database\Seeders\Questions\AnimalHerd\InitialHerdSetupQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireAnimalHerdQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AnimalIdentityQuestionsSeeder::class,
            AnimalSourceQuestionsSeeder::class,
            AnimalPedigreeQuestionsSeeder::class,
            InitialHerdSetupQuestionsSeeder::class,
        ]);
    }
}
