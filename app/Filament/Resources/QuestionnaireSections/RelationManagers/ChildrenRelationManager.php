<?php

namespace App\Filament\Resources\QuestionnaireSections\RelationManagers;

use App\Models\QuestionnaireSection;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $title = null;

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof QuestionnaireSection
            && $ownerRecord->parent_id === null
            && is_a($pageClass, EditRecord::class, true)
            && parent::canViewForRecord($ownerRecord, $pageClass);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament/resources/questionnaire_sections.fields.children_count');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('questions'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament/resources/questionnaire_sections.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament/resources/questionnaire_sections.fields.sort_order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label(__('filament/resources/questionnaire_sections.fields.questions_count')),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('edit')
                    ->label(__('filament/resources/questionnaire_sections.model_label'))
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (QuestionnaireSection $record): string => \App\Filament\Resources\QuestionnaireSections\QuestionnaireSectionResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([])
            ->defaultSort('sort_order')
            ->defaultSort('id')
            ->paginatedWhileReordering()
            ->reorderable('sort_order');
    }
}
