<?php

namespace App\Filament\Resources\QuestionnaireAnswers;

use App\Filament\Resources\QuestionnaireAnswers\Pages\CreateQuestionnaireAnswer;
use App\Filament\Resources\QuestionnaireAnswers\Pages\EditQuestionnaireAnswer;
use App\Filament\Resources\QuestionnaireAnswers\Pages\ListQuestionnaireAnswers;
use App\Filament\Resources\QuestionnaireAnswers\Schemas\QuestionnaireAnswerForm;
use App\Filament\Resources\QuestionnaireAnswers\Tables\QuestionnaireAnswersTable;
use App\Models\QuestionnaireAnswer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionnaireAnswerResource extends Resource
{
    protected static ?string $model = QuestionnaireAnswer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function form(Schema $schema): Schema
    {
        return QuestionnaireAnswerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionnaireAnswersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionnaireAnswers::route('/'),
            'create' => CreateQuestionnaireAnswer::route('/create'),
            'edit' => EditQuestionnaireAnswer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/questionnaire_answers.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/questionnaire_answers.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/questionnaire_answers.plural_model_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/resources/questionnaire_answers.navigation_group');
    }
}
