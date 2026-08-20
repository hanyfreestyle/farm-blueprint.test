<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Temporary review mode:
         * Seed only "بيانات المزرعة" while the remaining Master Data
         * question groups are being redesigned and approved one by one.
         */
        $this->call([
            FarmDataQuestionsSeeder::class,
        ]);
    }
}
