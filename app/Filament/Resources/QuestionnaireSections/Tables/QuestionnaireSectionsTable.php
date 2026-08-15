<?php

namespace App\Filament\Resources\QuestionnaireSections\Tables;

use App\Models\QuestionnaireSection;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class QuestionnaireSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament/resources/questionnaire_sections.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section_type')
                    ->label(__('filament/resources/questionnaire_sections.fields.section_type'))
                    ->getStateUsing(fn (QuestionnaireSection $record): string => $record->parent_id === null
                        ? __('filament/resources/questionnaire_sections.types.main')
                        : __('filament/resources/questionnaire_sections.types.subsection'))
                    ->badge(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('filament/resources/questionnaire_sections.fields.parent_display'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament/resources/questionnaire_sections.fields.sort_order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('children_count')
                    ->label(__('filament/resources/questionnaire_sections.fields.children_count'))
                    ->counts('children'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label(__('filament/resources/questionnaire_sections.fields.questions_count'))
                    ->counts('questions'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/questionnaire_sections.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section_type')
                    ->label(__('filament/resources/questionnaire_sections.fields.section_type'))
                    ->options([
                        'main' => __('filament/resources/questionnaire_sections.types.main'),
                        'subsection' => __('filament/resources/questionnaire_sections.types.subsection'),
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'main' => $query->whereNull('parent_id'),
                            'subsection' => $query->whereNotNull('parent_id'),
                            default => $query,
                        };
                    }),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label(__('filament/resources/questionnaire_sections.fields.parent_id'))
                    ->options(QuestionnaireSection::query()->mainSections()->orderBy('sort_order')->orderBy('id')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()
                    ->iconButton()
                    ->before(function (DeleteAction $action, QuestionnaireSection $record): void {
                        if ($record->children()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title(__('filament/resources/questionnaire_sections.messages.delete_has_children'))
                                ->send();

                            $action->cancel();
                        }

                        if ($record->questions()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title(__('filament/resources/questionnaire_sections.messages.delete_has_questions'))
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->defaultSort('sort_order')
            ->defaultSort('id');
    }
}
