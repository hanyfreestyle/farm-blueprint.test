<?php

namespace App\Filament\Resources\QuestionnaireQuestions\Pages;

use App\Filament\Resources\QuestionnaireQuestions\QuestionnaireQuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListQuestionnaireQuestions extends ListRecords
{
    protected static string $resource = QuestionnaireQuestionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
