<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Workflow\AnimalIntakeQuestionsSeeder;
use Database\Seeders\Questions\Workflow\BirthLitterQuestionsSeeder;
use Database\Seeders\Questions\Workflow\HousingMovementQuestionsSeeder;
use Database\Seeders\Questions\Workflow\MatingAttemptsQuestionsSeeder;
use Database\Seeders\Questions\Workflow\OperationalMeasurementsQuestionsSeeder;
use Database\Seeders\Questions\Workflow\PregnancyFollowUpQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireWorkflowQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AnimalIntakeQuestionsSeeder::class,
            HousingMovementQuestionsSeeder::class,
            OperationalMeasurementsQuestionsSeeder::class,
            MatingAttemptsQuestionsSeeder::class,
            PregnancyFollowUpQuestionsSeeder::class,
            BirthLitterQuestionsSeeder::class,
        ]);
    }
}
