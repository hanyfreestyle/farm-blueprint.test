<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @deprecated Use QuestionnaireMasterDataQuestionsSeeder instead.
 *
 * Kept temporarily so older tests or local commands that still reference the
 * previous class name do not break while the project moves to the clearer
 * seeder naming.
 */
class QuestionnaireFarmQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireMasterDataQuestionsSeeder::class);
    }
}
