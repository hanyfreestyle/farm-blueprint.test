<?php

namespace App\Filament\Resources\QuestionnaireSections\Pages;

use App\Filament\Resources\QuestionnaireSections\QuestionnaireSectionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateQuestionnaireSection extends CreateRecord
{
    protected static string $resource = QuestionnaireSectionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
