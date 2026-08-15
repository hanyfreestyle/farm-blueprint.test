<?php

namespace App\FilamentHelpers\Form\Repeater;

use App\Enums\Setting\EnumsSocialPlatform;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SocialPlatformRepeater {
  public int $setColumns = 2;

  public static function make(): static {
    return new static();
  }



#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function setColumns(int $value): static {
    $this->setColumns = $value;
    return $this;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(string $name): array {

    return [
      Repeater::make($name)
        ->label(__('filament/resources/users.fields.social'))
        ->hiddenLabel()
        ->itemLabel(fn (array $state): ?string => isset($state['platform']) ? EnumsSocialPlatform::tryFrom($state['platform'])?->getLabel() : __('filament/resources/users.actions.add_social'))
        ->collapsed()
        ->schema([
          Select::make('platform')
            ->label(__('filament/resources/users.fields.social_platform'))
            ->searchable()
            ->preload()
            ->options(
              collect(EnumsSocialPlatform::cases())
                ->mapWithKeys(fn (EnumsSocialPlatform $platform): array => [$platform->value => $platform->getLabel()])
                ->all()
            )
            ->required()
            ->live()
            ->afterStateUpdated(function (callable $set, $state): void {
              $template = match ($state) {
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://www.twitter.com/',
                'instagram' => 'https://www.instagram.com/',
                'youtube' => 'https://www.youtube.com/',
                'linkedin' => 'https://www.linkedin.com/',
                'tiktok' => 'https://www.tiktok.com/@',
                'snapchat' => 'https://www.snapchat.com/add/',
                'pinterest' => 'https://www.pinterest.com/',
                'threads' => 'https://www.threads.net/@',
                default => '',
              };

              $set('url', $template);
            }),

          TextInput::make('url')
            ->label(__('filament/resources/users.fields.social_url'))
            ->extraAttributes(fn () => rtlIfArabic('en'))
            ->required(),
        ])
        ->columns($this->setColumns)
        ->columnSpanFull()
        ->addActionLabel(__('filament/resources/users.actions.add_social'))
        ->default([]),
    ];
  }
}
