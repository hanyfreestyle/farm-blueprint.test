<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\BarnDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmQuestionsSeeder extends Seeder {

  public function run(): void {
    $this->call([
      FarmDataQuestionsSeeder::class,
      BarnDataQuestionsSeeder::class,
    ]);


  }

}