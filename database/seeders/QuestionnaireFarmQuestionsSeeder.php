<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @deprecated Seed the explicit question groups through
 * QuestionnaireMasterDataQuestionsSeeder and
 * QuestionnaireFarmStructureQuestionsSeeder instead.
 *
 * Kept temporarily so older tests or local commands that still reference the
 * previous class name continue to seed the same approved questionnaire scope.
 */
class QuestionnaireFarmQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuestionnaireMasterDataQuestionsSeeder::class,
            QuestionnaireFarmStructureQuestionsSeeder::class,
        ]);
    }
}
