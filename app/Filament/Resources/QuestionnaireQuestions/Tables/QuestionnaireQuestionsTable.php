<?php

namespace App\Filament\Resources\QuestionnaireQuestions\Tables;

use App\Filament\Resources\QuestionnaireQuestions\Schemas\QuestionnaireQuestionForm;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class QuestionnaireQuestionsTable {
  public static function configure(Table $table): Table {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
        Tables\Columns\TextColumn::make('title')
          ->label(__('filament/resources/questionnaire_questions.fields.title'))
          ->searchable()

          ->wrap(),
        Tables\Columns\TextColumn::make('section.parent.name')
          ->label(__('filament/resources/questionnaire_questions.fields.main_section_id'))
          ->limit(10)
          ->placeholder('-'),
        Tables\Columns\TextColumn::make('section.name')
          ->label(__('filament/resources/questionnaire_questions.fields.section_id'))

          ->placeholder('-'),
        Tables\Columns\TextColumn::make('type')
          ->label(__('filament/resources/questionnaire_questions.fields.type'))
          ->formatStateUsing(fn (QuestionnaireQuestion $record): string => $record->type?->label() ?? (string)$record->type)
          ->badge(),
        Tables\Columns\IconColumn::make('is_required')
          ->label(__('filament/resources/questionnaire_questions.fields.is_required'))
          ->boolean(),
        Tables\Columns\TextColumn::make('sort_order')
          ->label(__('filament/resources/questionnaire_questions.fields.sort_order'))
          ->sortable(),
        Tables\Columns\TextColumn::make('dependencyQuestion.title')
          ->label(__('filament/resources/questionnaire_questions.fields.depends_on_question_id'))
          ->placeholder('-')
          ->wrap()
          ->toggleable(),
        Tables\Columns\TextColumn::make('report_category')
          ->label(__('filament/resources/questionnaire_questions.fields.report_category'))
          ->formatStateUsing(fn (?string $state): string => filled($state)
            ? (QuestionnaireQuestionForm::getReportCategoryOptions()[$state] ?? $state)
            : __('filament/resources/questionnaire_questions.values.unspecified'))
          ->toggleable(),
        Tables\Columns\TextColumn::make('target_entity')
          ->label(__('filament/resources/questionnaire_questions.fields.target_entity'))
          ->placeholder('-')
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\IconColumn::make('has_answer')
          ->label(__('filament/resources/questionnaire_questions.fields.has_answer'))
          ->getStateUsing(fn (QuestionnaireQuestion $record): bool => $record->answer()->exists())
          ->boolean(),
        Tables\Columns\IconColumn::make('needs_review')
          ->label(__('filament/resources/questionnaire_questions.fields.needs_review'))
          ->getStateUsing(fn (QuestionnaireQuestion $record): bool => (bool)$record->answer?->needs_review)
          ->boolean(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('main_section')
          ->label(__('filament/resources/questionnaire_questions.fields.main_section_id'))
          ->options(QuestionnaireSection::query()->mainSections()->orderBy('sort_order')->orderBy('id')->pluck('name', 'id')->all())
          ->query(fn ($query, array $data) => filled($data['value'] ?? null)
            ? $query->whereHas('section', fn ($subQuery) => $subQuery->where('parent_id', $data['value']))
            : $query)
          ->searchable()
          ->preload(),
        Tables\Filters\SelectFilter::make('section_id')
          ->label(__('filament/resources/questionnaire_questions.fields.section_id'))
          ->relationship('section', 'name')
          ->searchable()
          ->preload(),
        Tables\Filters\SelectFilter::make('type')
          ->label(__('filament/resources/questionnaire_questions.fields.type'))
          ->options(\App\Enums\Questionnaire\QuestionType::options())
          ->searchable()
          ->preload(),
        Tables\Filters\TernaryFilter::make('is_required')
          ->label(__('filament/resources/questionnaire_questions.fields.is_required')),
        Tables\Filters\SelectFilter::make('report_category')
          ->label(__('filament/resources/questionnaire_questions.fields.report_category'))
          ->options(QuestionnaireQuestionForm::getReportCategoryOptions())
          ->searchable()
          ->preload(),
      ], layout: FiltersLayout::Modal)
      ->filtersFormColumns(4)
      ->persistFiltersInSession()
      ->persistSearchInSession()
      ->persistSortInSession()
      ->recordActions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ])
      ->defaultSort('sort_order')
      ->defaultSort('id');
  }
}
