<?php

namespace App\Traits\Admin\FormAction;

use Filament\Actions\Action;

trait WithSaveAndCreateAnother {
  protected function getFormActions(): array {
    return [
      Action::make('saveAndCreateAnother')
        ->label(__('default/lang.but.save_and_create_another'))
        ->color('warning')
        ->action('saveAndCreateAnother'),
      ...parent::getFormActions(),
    ];
  }

  public function saveAndCreateAnother(): void {
    $this->createAnother();
    $this->redirect($this->getResource()::getUrl('create')); // التوجيه إلى index
  }

  //customize redirect after create
  public function getRedirectUrl(): string {
    return $this->getResource()::getUrl('index');
  }
}
