<?php

namespace App\Filament\Resources\QuestionnaireSections\Pages;

use App\Filament\Resources\QuestionnaireSections\QuestionnaireSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditQuestionnaireSection extends EditRecord
{
    protected static string $resource = QuestionnaireSectionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action): void {
                    $record = $this->getRecord();

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
        ];
    }
}
