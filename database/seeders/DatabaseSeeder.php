<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Current local development workflow:
     *   php artisan migrate:refresh --seed
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            UploadFilterSeeder::class,
            QuestionnaireSectionSeeder::class,
            QuestionnaireQuestionsSeeder::class,
        ]);
    }
}
