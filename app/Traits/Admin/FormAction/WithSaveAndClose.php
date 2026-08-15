<?php

namespace App\Traits\Admin\FormAction;

use Filament\Actions\Action;

trait WithSaveAndClose {
  protected function getFormActions(): array {
    return [
      Action::make('saveAndClose')
        ->label(__('default/lang.but.save_and_close'))
        ->color('warning')
        ->action('saveAndClose'),
      ...parent::getFormActions(),
    ];
  }

  public function saveAndClose(): void {
    $this->save();
    $this->redirect($this->getResource()::getUrl('index'));
  }
}
