<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\OperationalActivitiesQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Review mode:
         * Seed only the question groups that have already been redesigned
         * and approved for the current Master Data review flow.
         */
        $this->call([
            FarmDataQuestionsSeeder::class,
            OperationalActivitiesQuestionsSeeder::class,
        ]);
    }
}
