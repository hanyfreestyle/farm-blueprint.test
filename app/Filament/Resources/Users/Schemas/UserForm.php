<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Setting\EnumsLocale;
use App\FilamentHelpers\Form\Editors\CKEditor4;
use App\FilamentHelpers\Form\Repeater\SocialPlatformRepeater;
use App\FilamentHelpers\UploadFile\WebpUploadFixedSize;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class UserForm {
  public static function configure(Schema $schema): Schema {
    return $schema
      ->components([

        Group::make()->schema([

          Group::make()->schema([

            Section::make(__('filament/resources/users.sections.details'))
              ->schema([
                TextInput::make('name')
                  ->label(__('filament/resources/users.fields.name'))
                  ->required()
                  ->maxLength(255),
                TextInput::make('email')
                  ->label(__('filament/resources/users.fields.email'))
                  ->email()
                  ->required()
                  ->maxLength(255)
                  ->unique(ignoreRecord: true)
                  ->extraAttributes(fn (): array => rtlIfArabic('en')),
                PhoneInput::make('phone')
                  ->label(__('filament/resources/users.fields.phone'))
                  ->countryStatePath('phone_country')
                  ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                  ->inputNumberFormat(PhoneInputNumberType::E164)
                  ->defaultCountry('EG')
                  ->unique(User::class, 'phone', ignoreRecord: true)
                  ->nullable(),
                TextInput::make('phone_country')
                  ->label(__('filament/resources/users.fields.phone_country'))
                  ->disabled()
                  ->dehydrated()
                  ->maxLength(8),

              ])
              ->columns(2),


            Section::make(__('filament/resources/users.sections.security'))
              ->schema([
                TextInput::make('password')
                  ->label(__('filament/resources/users.fields.password'))
                  ->password()
                  ->revealable()
                  ->statePath('password')
                  ->formatStateUsing(fn (): string => '')
                  ->autocomplete('new-password')
                  ->required(fn (string $operation): bool => $operation === 'create')
                  ->rule(Password::defaults())
                  ->dehydrated(fn (?string $state): bool => filled($state))
                  ->extraAttributes(fn (): array => rtlIfArabic('en')),
              ])
              ->columns(1),

          ])->columnSpan(5),

          Group::make()->schema([

            Section::make(__('filament/resources/users.sections.profile'))
              ->schema([
                ...WebpUploadFixedSize::make()
                  ->setFileName('avatar_url')
                  ->setFileLabel(__('filament/resources/users.fields.avatar_url'))
                  ->setDiskDir('root_folder')
                  ->setDiskVisibility('public')
                  ->setUploadDirectory('users/avatars')
                  ->setAspectRatio('1:1')
                  ->setResize(600, 600, 90)
                  ->setThumbnail()
                  ->setThumbnailSize(300, 300)
                  ->getColumns(),
              ]),

            Section::make(__('filament/resources/users.sections.roles'))
              ->schema([
                Select::make('roles')
                  ->label(__('filament/resources/users.fields.roles'))
                  ->relationship('roles', 'name')
                  ->preload()
                  ->searchable()
                  ->multiple()
                  ->required()
                  ->disabled(fn (?User $record): bool => $record?->isPrimaryUser() ?? false)
                  ->helperText(fn (?User $record): ?string => $record?->isPrimaryUser()
                    ? __('filament/resources/users.hints.primary_user_roles')
                    : __('filament/resources/users.hints.roles')),
              ]),

          ])->columnSpan(3),


        ])->columnSpanFull()->columns(8),


        Group::make()->schema([

          Group::make()->schema([

            Section::make(__('filament/resources/users.sections.author'))
              ->schema([
                Tabs::make('author_tabs')
                  ->tabs(self::getAuthorTabs()),
              ]),

          ])->columnSpan(5),

          Group::make()->schema([
            Section::make(__('filament/resources/users.fields.social'))
              ->schema([
                ...SocialPlatformRepeater::make()->setColumns(1)->getColumns('social'),
              ]),

          ])->columnSpan(3),


        ])->visible(fn (): bool => (bool)config('core.users.author_profile_enabled'))
          ->columnSpanFull()->columns(8),


      ]);
  }

  /**
   * @return array<int, Tab>
   */
  protected static function getAuthorTabs(): array {
    return collect(self::getActiveLocales())
      ->map(function (string $locale): Tab {
        $label = EnumsLocale::tryFrom($locale)?->label() ?? strtoupper($locale);

        return Tab::make($label)
          ->schema([
            TextInput::make("slug.$locale")
              ->label(__('filament/resources/users.fields.slug'))
              ->live(onBlur: true)
              ->afterStateUpdated(fn (?string $state, callable $set) => $set("slug.$locale", filled($state) ? Url_Slug($state) : null))
              ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Url_Slug($state) : null)
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            TextInput::make("author_name.$locale")
              ->label(__('filament/resources/users.fields.author_name'))
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            TextInput::make("job_title.$locale")
              ->label(__('filament/resources/users.fields.job_title'))
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            Textarea::make("short_des.$locale")
              ->label(__('filament/resources/users.fields.short_des'))
              ->rows(4)
              ->columnSpanFull()
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            Textarea::make("des.$locale")
              ->label(__('filament/resources/users.fields.des'))
              ->rows(6)
              ->columnSpanFull()
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            TextInput::make("g_h1.$locale")
              ->label(__('filament/resources/users.fields.g_h1'))
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),
            TextInput::make("g_title.$locale")
              ->label(__('filament/resources/users.fields.g_title'))
              ->extraAttributes(fn (): array => rtlIfArabic($locale)),


            CKEditor4::make("g_des.$locale")
              ->label(__('filament/resources/users.fields.g_des'))
              ->required()
              ->columnSpanFull()
              ->setEditorHeight(250)
              ->reactive()
              ->extraAttributes([
                'locale' => $locale,
              ])
          ])
          ->columns(2);
      })
      ->values()
      ->all();
  }

  /**
   * @return array<int, string>
   */
  protected static function getActiveLocales(): array {
    $locales = getProjectActiveLocales();

    return $locales !== [] ? $locales : array_column(EnumsLocale::cases(), 'value');
  }
}
