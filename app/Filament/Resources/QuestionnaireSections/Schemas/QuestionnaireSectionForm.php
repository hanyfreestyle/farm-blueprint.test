<?php

namespace App\Filament\Resources\QuestionnaireSections\Schemas;

use App\Models\QuestionnaireSection;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionnaireSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament/resources/questionnaire_sections.sections.basic'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('filament/resources/questionnaire_sections.fields.name'))
                        ->required()
                        ->maxLength(255),
                    Select::make('parent_id')
                        ->label(__('filament/resources/questionnaire_sections.fields.parent_id'))
                        ->options(fn (?QuestionnaireSection $record): array => self::getAvailableParentOptions($record))
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText(__('filament/resources/questionnaire_sections.hints.parent_id')),
                    MarkdownEditor::make('description')
                        ->label(__('filament/resources/questionnaire_sections.fields.description'))
                        ->nullable()
                        ->columnSpanFull(),
                    TextInput::make('sort_order')
                        ->label(__('filament/resources/questionnaire_sections.fields.sort_order'))
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    /**
     * @return array<int|string, string>
     */
    public static function getAvailableParentOptions(?QuestionnaireSection $record = null): array
    {
        return QuestionnaireSection::query()
            ->mainSections()
            ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->all();
    }
}
