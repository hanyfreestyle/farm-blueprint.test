<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionnaireQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuestionnaireMasterDataQuestionsSeeder::class,
            QuestionnaireFarmStructureQuestionsSeeder::class,
        ]);
    }
}
