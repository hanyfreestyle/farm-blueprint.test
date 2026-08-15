<?php

namespace App\Filament\Resources\QuestionnaireQuestions\Schemas;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionnaireQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament/resources/questionnaire_questions.sections.basic'))
                ->schema([
                    Select::make('main_section_id')
                        ->label(__('filament/resources/questionnaire_questions.fields.main_section_id'))
                        ->options(self::getMainSectionOptions())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(fn (callable $set) => $set('section_id', null)),
                    Select::make('section_id')
                        ->label(__('filament/resources/questionnaire_questions.fields.section_id'))
                        ->options(fn (callable $get): array => self::getSubsectionOptions((int) ($get('main_section_id') ?? 0)))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText(__('filament/resources/questionnaire_questions.hints.section_id')),
                    Textarea::make('title')
                        ->label(__('filament/resources/questionnaire_questions.fields.title'))
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('help_text')
                        ->label(__('filament/resources/questionnaire_questions.fields.help_text'))
                        ->nullable()
                        ->rows(4)
                        ->columnSpanFull(),
                    Select::make('type')
                        ->label(__('filament/resources/questionnaire_questions.fields.type'))
                        ->options(QuestionType::options())
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live(),
                    Toggle::make('is_required')
                        ->label(__('filament/resources/questionnaire_questions.fields.is_required'))
                        ->inline(false),
                    TextInput::make('sort_order')
                        ->label(__('filament/resources/questionnaire_questions.fields.sort_order'))
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->columns(2),
            Section::make(__('filament/resources/questionnaire_questions.sections.options'))
                ->schema([
                    Repeater::make('options')
                        ->relationship('options')
                        ->addActionLabel(__('filament/resources/questionnaire_questions.actions.add_option'))
                        ->reorderableWithDragAndDrop()
                        ->defaultItems(0)
                        ->collapsed()
                        ->collapsible()
                        ->visible(fn (?QuestionnaireQuestion $record, callable $get): bool => self::shouldShowOptionsRepeater($record, $get('type')))
                        ->helperText(__('filament/resources/questionnaire_questions.hints.options'))
                        ->schema([
                            TextInput::make('label')
                                ->label(__('filament/resources/questionnaire_questions.fields.option_label'))
                                ->required()
                                ->live(onBlur: true),
                            TextInput::make('value')
                                ->label(__('filament/resources/questionnaire_questions.fields.option_value'))
                                ->required()
                                ->afterStateHydrated(function (?string $state, callable $set, callable $get): void {
                                    if (filled($state)) {
                                        return;
                                    }

                                    $label = $get('label');

                                    if (filled($label)) {
                                        $set('value', str((string) $label)->slug('_')->toString());
                                    }
                                })
                                ->helperText(__('filament/resources/questionnaire_questions.hints.option_value')),
                            TextInput::make('sort_order')
                                ->label(__('filament/resources/questionnaire_questions.fields.sort_order'))
                                ->numeric()
                                ->default(0)
                                ->required(),
                        ])
                        ->columns(3),
                    Placeholder::make('options_safety_notice')
                        ->label('')
                        ->content(__('filament/resources/questionnaire_questions.messages.options_type_change'))
                        ->visible(fn (?QuestionnaireQuestion $record, callable $get): bool => self::shouldShowOptionsWarning($record, $get('type'))),
                ]),
            Section::make(__('filament/resources/questionnaire_questions.sections.dependency'))
                ->schema([
                    Toggle::make('has_dependency')
                        ->label(__('filament/resources/questionnaire_questions.fields.has_dependency'))
                        ->dehydrated(false)
                        ->live()
                        ->afterStateHydrated(function (?QuestionnaireQuestion $record, callable $set): void {
                            $set('has_dependency', $record?->depends_on_question_id !== null);
                        })
                        ->afterStateUpdated(function (bool $state, callable $set): void {
                            if ($state) {
                                return;
                            }

                            $set('depends_on_question_id', null);
                            $set('dependency_operator', null);
                            $set('dependency_value', null);
                            $set('dependency_value_select', null);
                            $set('dependency_value_text', null);
                        }),
                    Select::make('depends_on_question_id')
                        ->label(__('filament/resources/questionnaire_questions.fields.depends_on_question_id'))
                        ->options(fn (?QuestionnaireQuestion $record, callable $get): array => self::getDependencyQuestionOptions($record, (int) ($get('section_id') ?? 0)))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->visible(fn (callable $get): bool => (bool) $get('has_dependency')),
                    Select::make('dependency_operator')
                        ->label(__('filament/resources/questionnaire_questions.fields.dependency_operator'))
                        ->options(QuestionDependencyOperator::options())
                        ->searchable()
                        ->preload()
                        ->visible(fn (callable $get): bool => (bool) $get('has_dependency')),
                    Select::make('dependency_value_select')
                        ->label(__('filament/resources/questionnaire_questions.fields.dependency_value'))
                        ->options(fn (?QuestionnaireQuestion $record, callable $get): array => self::getDependencyValueOptions($record, $get))
                        ->dehydrated(false)
                        ->searchable()
                        ->preload()
                        ->visible(fn (?QuestionnaireQuestion $record, callable $get): bool => self::shouldUseDependencyValueSelect($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireQuestion $record, callable $set): void {
                            $set('dependency_value_select', $record?->dependency_value);
                        }),
                    TextInput::make('dependency_value_text')
                        ->label(__('filament/resources/questionnaire_questions.fields.dependency_value'))
                        ->dehydrated(false)
                        ->visible(fn (?QuestionnaireQuestion $record, callable $get): bool => self::shouldUseDependencyValueText($record, $get))
                        ->afterStateHydrated(function (?QuestionnaireQuestion $record, callable $set): void {
                            $set('dependency_value_text', $record?->dependency_value);
                        }),
                    Hidden::make('dependency_value'),
                ])
                ->columns(2),
            Section::make(__('filament/resources/questionnaire_questions.sections.report'))
                ->schema([
                    Select::make('report_category')
                        ->label(__('filament/resources/questionnaire_questions.fields.report_category'))
                        ->options(self::getReportCategoryOptions())
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    TextInput::make('target_entity')
                        ->label(__('filament/resources/questionnaire_questions.fields.target_entity'))
                        ->nullable()
                        ->maxLength(255),
                ])
                ->columns(2),
        ]);
    }

    /**
     * @return array<int|string, string>
     */
    public static function getMainSectionOptions(): array
    {
        return QuestionnaireSection::query()
            ->mainSections()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return array<int|string, string>
     */
    public static function getSubsectionOptions(int $mainSectionId): array
    {
        if ($mainSectionId <= 0) {
            return [];
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSectionId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->all();
    }

    public static function shouldShowOptionsRepeater(?QuestionnaireQuestion $record, mixed $type): bool
    {
        $questionType = $type instanceof QuestionType ? $type : QuestionType::tryFrom((string) $type);

        if (in_array($questionType, [QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::SELECT], true)) {
            return true;
        }

        return $record?->options()->exists() ?? false;
    }

    public static function shouldShowOptionsWarning(?QuestionnaireQuestion $record, mixed $type): bool
    {
        $questionType = $type instanceof QuestionType ? $type : QuestionType::tryFrom((string) $type);

        return ($record?->options()->exists() ?? false)
            && ! in_array($questionType, [QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::SELECT], true);
    }

    /**
     * @return array<int|string, string>
     */
    public static function getDependencyQuestionOptions(?QuestionnaireQuestion $record, int $sectionId): array
    {
        if ($sectionId <= 0) {
            return [];
        }

        return QuestionnaireQuestion::query()
            ->where('section_id', $sectionId)
            ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('title', 'id')
            ->all();
    }

    /**
     * @param  callable(string): mixed  $get
     * @return array<string, string>
     */
    public static function getDependencyValueOptions(?QuestionnaireQuestion $record, callable $get): array
    {
        $dependencyQuestionId = (int) ($get('depends_on_question_id') ?? 0);

        if ($dependencyQuestionId <= 0) {
            return [];
        }

        $dependencyQuestion = QuestionnaireQuestion::query()->find($dependencyQuestionId);

        return $dependencyQuestion?->getDependencyValueOptions() ?? [];
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function shouldUseDependencyValueSelect(?QuestionnaireQuestion $record, callable $get): bool
    {
        $dependencyQuestionId = (int) ($get('depends_on_question_id') ?? 0);

        if ($dependencyQuestionId <= 0) {
            return false;
        }

        $dependencyQuestion = QuestionnaireQuestion::query()->find($dependencyQuestionId);

        if (! $dependencyQuestion instanceof QuestionnaireQuestion) {
            return false;
        }

        return $dependencyQuestion->isYesNoType() || $dependencyQuestion->isOptionBasedType();
    }

    /**
     * @param  callable(string): mixed  $get
     */
    public static function shouldUseDependencyValueText(?QuestionnaireQuestion $record, callable $get): bool
    {
        $dependencyQuestionId = (int) ($get('depends_on_question_id') ?? 0);

        if ($dependencyQuestionId <= 0) {
            return false;
        }

        $dependencyQuestion = QuestionnaireQuestion::query()->find($dependencyQuestionId);

        if (! $dependencyQuestion instanceof QuestionnaireQuestion) {
            return false;
        }

        return $dependencyQuestion->isTextLikeDependencyType();
    }

    /**
     * @return array<string, string>
     */
    public static function getReportCategoryOptions(): array
    {
        return [
            'field' => __('filament/resources/questionnaire_questions.report_categories.field'),
            'lookup' => __('filament/resources/questionnaire_questions.report_categories.lookup'),
            'relationship' => __('filament/resources/questionnaire_questions.report_categories.relationship'),
            'workflow' => __('filament/resources/questionnaire_questions.report_categories.workflow'),
            'rule' => __('filament/resources/questionnaire_questions.report_categories.rule'),
            'alert' => __('filament/resources/questionnaire_questions.report_categories.alert'),
            'report' => __('filament/resources/questionnaire_questions.report_categories.report'),
            'general' => __('filament/resources/questionnaire_questions.report_categories.general'),
        ];
    }
}
