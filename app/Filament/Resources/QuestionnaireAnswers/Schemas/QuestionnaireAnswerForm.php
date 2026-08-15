<?php

namespace App\Filament\Resources\QuestionnaireAnswers\Schemas;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionnaireAnswerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament/resources/questionnaire_answers.sections.basic'))
                ->schema([
                    Select::make('question_id')
                        ->label(__('filament/resources/questionnaire_answers.fields.question_id'))
                        ->options(fn (?QuestionnaireAnswer $record): array => self::getQuestionOptions($record))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->disabled(fn (?QuestionnaireAnswer $record): bool => $record !== null)
                        ->dehydrated(),
                    TextInput::make('value_text')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesTextInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_text', is_scalar($record?->value) ? (string) $record?->value : null);
                        }),
                    Textarea::make('value_textarea')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->dehydrated(false)
                        ->rows(5)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesTextarea($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_textarea', is_scalar($record?->value) ? (string) $record?->value : null);
                        }),
                    TextInput::make('value_number')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->numeric()
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesNumberInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_number', is_scalar($record?->value) ? $record?->value : null);
                        }),
                    DatePicker::make('value_date')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesDateInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_date', is_scalar($record?->value) ? (string) $record?->value : null);
                        }),
                    Toggle::make('value_yes_no')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->dehydrated(false)
                        ->inline(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesYesNoInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_yes_no', filter_var($record?->value, FILTER_VALIDATE_BOOLEAN));
                        }),
                    Radio::make('value_option')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->options(fn (?QuestionnaireAnswer $record, callable $get): array => self::getQuestionValueOptions($record, $get))
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesOptionInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_option', is_scalar($record?->value) ? (string) $record?->value : null);
                        }),
                    CheckboxList::make('value_options')
                        ->label(__('filament/resources/questionnaire_answers.fields.value'))
                        ->options(fn (?QuestionnaireAnswer $record, callable $get): array => self::getQuestionValueOptions($record, $get))
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireAnswer $record, callable $get): bool => self::usesMultiOptionInput($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireAnswer $record, callable $set): void {
                            $set('value_options', is_array($record?->value) ? $record->value : []);
                        }),
                    Textarea::make('notes')
                        ->label(__('filament/resources/questionnaire_answers.fields.notes'))
                        ->rows(4)
                        ->columnSpanFull(),
                    Select::make('review_status')
                        ->label(__('filament/resources/questionnaire_answers.fields.review_status'))
                        ->options(AnswerReviewStatus::options())
                        ->required()
                        ->searchable()
                        ->preload(),
                    Hidden::make('value'),
                ])
                ->columns(2),
        ]);
    }

    /**
     * @return array<int|string, string>
     */
    public static function getQuestionOptions(?QuestionnaireAnswer $record = null): array
    {
        return QuestionnaireQuestion::query()
            ->when($record === null, fn ($query) => $query->whereDoesntHave('answer'))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('title', 'id')
            ->all();
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function resolveQuestion(?QuestionnaireAnswer $record, callable $get): ?QuestionnaireQuestion
    {
        if ($record?->question instanceof QuestionnaireQuestion) {
            return $record->question->loadMissing('options');
        }

        $questionId = (int) ($get('question_id') ?? 0);

        return $questionId > 0 ? QuestionnaireQuestion::query()->with('options')->find($questionId) : null;
    }

    /**
     * @param  callable(string): mixed  $get
     * @return array<string, string>
     */
    public static function getQuestionValueOptions(?QuestionnaireAnswer $record, callable $get): array
    {
        $question = self::resolveQuestion($record, $get);

        return $question?->getDependencyValueOptions() ?? [];
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesTextInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::TEXT;
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesTextarea(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::TEXTAREA;
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesNumberInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::NUMBER;
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesDateInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::DATE;
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesYesNoInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::YES_NO;
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesOptionInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return in_array(self::resolveQuestion($record, $get)?->type, [QuestionType::SINGLE_CHOICE, QuestionType::SELECT], true);
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function usesMultiOptionInput(?QuestionnaireAnswer $record, callable $get): bool
    {
        return self::resolveQuestion($record, $get)?->type === QuestionType::MULTI_CHOICE;
    }
}
