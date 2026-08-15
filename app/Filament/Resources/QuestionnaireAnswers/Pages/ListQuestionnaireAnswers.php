<?php

namespace App\Filament\Resources\QuestionnaireAnswers\Pages;

use App\Filament\Resources\QuestionnaireAnswers\QuestionnaireAnswerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListQuestionnaireAnswers extends ListRecords
{
    protected static string $resource = QuestionnaireAnswerResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
