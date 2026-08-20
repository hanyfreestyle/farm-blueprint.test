<?php

namespace App\Filament\Resources\QuestionnaireAnswers\Tables;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSection;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class QuestionnaireAnswersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('question.section.parent.name')
                    ->label(__('filament/resources/questionnaire_answers.fields.main_section'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('question.section.name')
                    ->label(__('filament/resources/questionnaire_answers.fields.subsection'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('question.title')
                    ->label(__('filament/resources/questionnaire_answers.fields.question'))
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('formatted_value')
                    ->label(__('filament/resources/questionnaire_answers.fields.readable_value'))
                    ->state(fn (QuestionnaireAnswer $record): string => $record->loadMissing('question.options')->formatValueForDisplay())
                    ->wrap(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('filament/resources/questionnaire_answers.fields.notes'))
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\IconColumn::make('needs_review')
                    ->label(__('filament/resources/questionnaire_answers.fields.needs_review'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('review_status')
                    ->label(__('filament/resources/questionnaire_answers.fields.review_status'))
                    ->formatStateUsing(fn (QuestionnaireAnswer $record): string => $record->review_status->label())
                    ->badge(),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label(__('filament/resources/questionnaire_answers.fields.reviewed_at'))
                    ->dateTime()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament/resources/questionnaire_answers.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('main_section')
                    ->label(__('filament/resources/questionnaire_answers.fields.main_section'))
                    ->options(QuestionnaireSection::query()->mainSections()->orderBy('sort_order')->orderBy('id')->pluck('name', 'id')->all())
                    ->query(fn ($query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereHas('question.section', fn ($subQuery) => $subQuery->where('parent_id', $data['value']))
                        : $query)
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('subsection')
                    ->label(__('filament/resources/questionnaire_answers.fields.subsection'))
                    ->query(function ($query, array $data) {
                        if (! filled($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->whereHas('question', fn ($subQuery) => $subQuery->where('section_id', $data['value']));
                    })
                    ->options(QuestionnaireSection::query()->subsections()->orderBy('sort_order')->orderBy('id')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('needs_review')
                    ->label(__('filament/resources/questionnaire_answers.fields.needs_review')),
                Tables\Filters\SelectFilter::make('review_status')
                    ->label(__('filament/resources/questionnaire_answers.fields.review_status'))
                    ->options(AnswerReviewStatus::options())
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('question_type')
                    ->label(__('filament/resources/questionnaire_answers.fields.question_type'))
                    ->options(QuestionType::options())
                    ->query(fn ($query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereHas('question', fn ($subQuery) => $subQuery->where('type', $data['value']))
                        : $query)
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::Modal)
            ->deferFilters(false)
            ->filtersFormColumns(4)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->recordActions([
                EditAction::make()->iconButton(),
                Action::make('mark_reviewed')
                    ->label(__('filament/resources/questionnaire_answers.actions.mark_reviewed'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (QuestionnaireAnswer $record): bool => $record->needs_review && $record->review_status === AnswerReviewStatus::PENDING)
                    ->action(fn (QuestionnaireAnswer $record) => $record->update(['review_status' => AnswerReviewStatus::REVIEWED])),
                Action::make('mark_pending')
                    ->label(__('filament/resources/questionnaire_answers.actions.mark_pending'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (QuestionnaireAnswer $record): bool => $record->review_status === AnswerReviewStatus::REVIEWED)
                    ->action(fn (QuestionnaireAnswer $record) => $record->update(['review_status' => AnswerReviewStatus::PENDING])),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
