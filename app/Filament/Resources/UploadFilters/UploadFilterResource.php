<?php

namespace App\Filament\Resources\UploadFilters;

use App\Enums\Setting\EnumsLocale;
use App\Enums\Status\EnumsActive;
use App\Enums\WebSetting\UploadFilter\EnumsAspectRatio;
use App\Enums\WebSetting\UploadFilter\EnumsFilterType;
use App\Filament\Resources\UploadFilters\Pages\CreateUploadFilter;
use App\Filament\Resources\UploadFilters\Pages\EditUploadFilter;
use App\Filament\Resources\UploadFilters\Pages\ListUploadFilters;
use App\Models\WebSetting\UploadFilter;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class UploadFilterResource extends Resource {
  protected static ?string $model = UploadFilter::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFunnel;

  public static function form(Schema $schema): Schema {
    return $schema->components([

      Group::make()->schema([
        Group::make()->schema([

          Section::make(__('filament/resources/upload_filters.sections.basic'))
            ->schema([
              TextInput::make('name')
                ->label(__('filament/resources/upload_filters.fields.name'))
                ->required()
                ->maxLength(255),
              Select::make('type')
                ->label(__('filament/resources/upload_filters.fields.type'))
                ->options(EnumsFilterType::options())
                ->default(EnumsFilterType::FilterType_1->value)
                ->required()
                ->searchable()
                ->preload(),
              ColorPicker::make('canvas_back')
                ->label(__('filament/resources/upload_filters.fields.canvas_back'))
                ->default('#ffffff'),
              Select::make('crop_aspect_ratio')
                ->label(__('filament/resources/upload_filters.fields.crop_aspect_ratio'))
                ->options(EnumsAspectRatio::options())
                ->searchable()
                ->preload(),
              TextInput::make('width')
                ->label(__('filament/resources/upload_filters.fields.width'))
                ->numeric()
                ->required(),
              TextInput::make('height')
                ->label(__('filament/resources/upload_filters.fields.height'))
                ->numeric()
                ->required(),
              TextInput::make('cat_id')
                ->label(__('filament/resources/upload_filters.fields.cat_id'))
                ->maxLength(255)
                ->columnSpanFull(),
            ])
            ->columns([
              'default' => 1,
              'md' => 2,
              'xl' => 3,
            ]),

          Section::make(__('filament/resources/upload_filters.sections.sizes'))
            ->schema([
              Repeater::make('sizes')
                ->relationship('sizes')
                ->addActionLabel(__('filament/resources/upload_filters.actions.add_size'))
                ->collapsed()
                ->collapsible()
                ->defaultItems(0)
                ->schema([
                  Select::make('type')
                    ->label(__('filament/resources/upload_filters.fields.type'))
                    ->options(EnumsFilterType::options())
                    ->default(EnumsFilterType::FilterType_1->value)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan([
                      'default' => 1,
                      'xl' => 2,
                    ]),
                  ColorPicker::make('canvas_back')
                    ->label(__('filament/resources/upload_filters.fields.canvas_back'))
                    ->default('#ffffff'),
                  TextInput::make('width')
                    ->label(__('filament/resources/upload_filters.fields.width'))
                    ->numeric()
                    ->required(),
                  TextInput::make('height')
                    ->label(__('filament/resources/upload_filters.fields.height'))
                    ->numeric()
                    ->required(),
                  Toggle::make('text_state')
                    ->label(__('filament/resources/upload_filters.fields.text_state'))
                    ->inline(false),
                  Toggle::make('watermark_state')
                    ->label(__('filament/resources/upload_filters.fields.watermark_state'))
                    ->inline(false),
                ])
                ->columns([
                  'default' => 1,
                  'md' => 2,
                  'xl' => 4,
                ])
                ->itemLabel(fn (array $state): ?string => filled($state['width'] ?? null) && filled($state['height'] ?? null)
                  ? "{$state['width']} x {$state['height']} - " . EnumsFilterType::tryFrom((int)($state['type'] ?? 1))?->label()
                  : null),
            ]),

        ])->columnSpan(5),


        Group::make()->schema([

          Section::make(__('filament/resources/upload_filters.sections.effects'))
            ->schema([
              Toggle::make('convert_state')
                ->label(__('filament/resources/upload_filters.fields.convert_state'))
                ->inline(false)
                ->default(true),
              TextInput::make('quality_val')
                ->label(__('filament/resources/upload_filters.fields.quality_val'))
                ->numeric()
                ->default(85)
                ->required(),
              Toggle::make('is_notes')
                ->label(__('filament/resources/upload_filters.fields.is_notes'))
                ->inline(false)
                ->default(false),
              Toggle::make('greyscale')
                ->label(__('filament/resources/upload_filters.fields.greyscale'))
                ->inline(false),
              Toggle::make('flip_state')
                ->label(__('filament/resources/upload_filters.fields.flip_state'))
                ->inline(false),
              Toggle::make('flip_v')
                ->label(__('filament/resources/upload_filters.fields.flip_v'))
                ->inline(false),
              Toggle::make('blur')
                ->label(__('filament/resources/upload_filters.fields.blur'))
                ->inline(false),
              TextInput::make('blur_size')
                ->label(__('filament/resources/upload_filters.fields.blur_size'))
                ->numeric()
                ->default(0),
              Toggle::make('pixelate')
                ->label(__('filament/resources/upload_filters.fields.pixelate'))
                ->inline(false),
              TextInput::make('pixelate_size')
                ->label(__('filament/resources/upload_filters.fields.pixelate_size'))
                ->numeric()
                ->default(5),
              Toggle::make('state')
                ->label(__('filament/resources/upload_filters.fields.state'))
                ->inline(false)
                ->default(true),
            ])
            ->columns(3),

          Section::make(__('filament/resources/upload_filters.sections.notes'))
            ->schema([
              Tabs::make('notes_tabs')
                ->tabs(
                  collect(EnumsLocale::options())
                    ->map(fn (string $label, string $locale) => Tab::make($label)
                      ->schema([
                        Textarea::make("notes.$locale")
                          ->label(__('filament/resources/upload_filters.fields.notes'))
                          ->rows(5)
                          ->extraAttributes(rtlIfArabic($locale)),
                      ]))
                    ->values()
                    ->all()
                ),
            ]),


        ])->columnSpan(3),

      ])->columnSpanFull()->columns(8),





    ]);
  }

  public static function table(Table $table): Table {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('ID')
          ->sortable(),
        Tables\Columns\TextColumn::make('name')
          ->label(__('filament/resources/upload_filters.fields.name'))
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('type')
          ->label(__('filament/resources/upload_filters.fields.type'))
          ->formatStateUsing(fn ($state): string => EnumsFilterType::tryFrom((int)$state)?->label() ?? (string)$state)
          ->description(fn (UploadFilter $record): string => "{$record->width}x{$record->height}"),
        Tables\Columns\TextColumn::make('crop_aspect_ratio')
          ->label(__('filament/resources/upload_filters.fields.crop_aspect_ratio')),
        Tables\Columns\TextColumn::make('sizes_count')
          ->counts('sizes')
          ->label(__('filament/resources/upload_filters.fields.sizes')),
        Tables\Columns\IconColumn::make('convert_state')
          ->label(__('filament/resources/upload_filters.fields.convert_state'))
          ->boolean(),
        Tables\Columns\IconColumn::make('state')
          ->label(__('filament/resources/upload_filters.fields.state'))
          ->boolean(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('type')
          ->label(__('filament/resources/upload_filters.fields.type'))
          ->options(EnumsFilterType::options())
          ->searchable()
          ->preload(),
        Tables\Filters\SelectFilter::make('state')
          ->label(__('filament/resources/upload_filters.fields.state'))
          ->options(EnumsActive::options()),
      ], layout: FiltersLayout::Modal)
      ->filtersFormColumns(4)
      ->persistFiltersInSession()
      ->persistSearchInSession()
      ->persistSortInSession()
      ->recordActions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('id', 'desc');
  }

  public static function getPages(): array {
    return [
      'index' => ListUploadFilters::route('/'),
      'create' => CreateUploadFilter::route('/create'),
      'edit' => EditUploadFilter::route('/{record}/edit'),
    ];
  }

  public static function getNavigationLabel(): string {
    return __('filament/resources/upload_filters.navigation_label');
  }

  public static function getModelLabel(): string {
    return __('filament/resources/upload_filters.model_label');
  }

  public static function getPluralModelLabel(): string {
    return __('filament/resources/upload_filters.plural_model_label');
  }

  public static function getNavigationGroup(): ?string {
    return __('filament/resources/upload_filters.navigation_group');
  }
}
