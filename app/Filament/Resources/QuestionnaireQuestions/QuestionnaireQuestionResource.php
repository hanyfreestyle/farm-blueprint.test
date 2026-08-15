<?php

namespace App\Filament\Resources\QuestionnaireQuestions;

use App\Filament\Resources\QuestionnaireQuestions\Pages\CreateQuestionnaireQuestion;
use App\Filament\Resources\QuestionnaireQuestions\Pages\EditQuestionnaireQuestion;
use App\Filament\Resources\QuestionnaireQuestions\Pages\ListQuestionnaireQuestions;
use App\Filament\Resources\QuestionnaireQuestions\Schemas\QuestionnaireQuestionForm;
use App\Filament\Resources\QuestionnaireQuestions\Tables\QuestionnaireQuestionsTable;
use App\Models\QuestionnaireQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionnaireQuestionResource extends Resource
{
    protected static ?string $model = QuestionnaireQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    public static function form(Schema $schema): Schema
    {
        return QuestionnaireQuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionnaireQuestionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionnaireQuestions::route('/'),
            'create' => CreateQuestionnaireQuestion::route('/create'),
            'edit' => EditQuestionnaireQuestion::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/questionnaire_questions.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/questionnaire_questions.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/questionnaire_questions.plural_model_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/resources/questionnaire_questions.navigation_group');
    }
}
