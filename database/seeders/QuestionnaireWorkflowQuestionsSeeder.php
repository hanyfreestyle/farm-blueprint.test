<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Workflow\AnimalIntakeQuestionsSeeder;
use Database\Seeders\Questions\Workflow\HousingMovementQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireWorkflowQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AnimalIntakeQuestionsSeeder::class,
            HousingMovementQuestionsSeeder::class,
        ]);
    }
}
