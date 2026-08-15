<?php

namespace App\Filament\Admin\Resources\WebSetting;

use App\Filament\Admin\Resources\WebSetting\WebSiteSettingsResource\Pages;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;

class WebSiteSettingsResource extends Resource implements HasShieldPermissions {
  use SmartResourceTrait;

  protected static ?string $model = WebSiteSettings::class;
  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function shouldRegisterNavigation(): bool {
    return false;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function getPermissionPrefixes(): array {
    return static::filterPermissions(
      skipKeys: ['view', 'view_any'],
      keepKeys: ['sort'],
      addKeys: [
        'placeIn' => 'before',
        'keys' => self::loadPermissions(),
      ],
    );
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function getNavigationGroup(): ?string {
    return __('filament/settings/webSetting.navigation_group');
  }

  public static function getNavigationLabel(): string {
    return __('filament/settings/webSetting.navigation_group');
  }

  public static function getModelLabel(): string {
    return __('filament/settings/webSetting.navigation_group');
  }

  public static function getPluralModelLabel(): string {
    return __('filament/settings/webSetting.navigation_group');
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function form(Form $form): Form {
    return $form->schema([


    ])->columns(3);
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function table(Table $table): Table {
    return $table
      ->columns([

      ])->filters([

      ])
      ->actions([
      ])
      ->bulkActions([

      ])
      ->defaultSort('id');
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

  public static function getRelations(): array {
    return [
      //
    ];
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function getPages(): array {
    return [
      'index' => Pages\ListWebSiteSettings::route('/'),
      'create' => Pages\CreateWebSiteSettings::route('/create'),
      'edit' => Pages\EditWebSiteSettings::route('/{record}/edit'),
    ];
  }



#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function getTableRecordUrl($record): ?string {
    return static::getUrl('edit', ['record' => $record->getKey()]);
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function loadPermissions(): array {
    $keys = ['web_site_settings'];

    if (File::isFile(base_path('app/Models/WebSetting/UploadFilterSize.php'))) {
      $keys = array_merge_recursive($keys, ['web_models_settings']);
      $keys = array_merge_recursive($keys, ['upload_filter']);
    }

    if (File::isFile(base_path('app/Models/WebSetting/DefPhoto.php'))) {
      $keys = array_merge_recursive($keys, ['default_photo']);
    }

    if (File::isFile(base_path('app/Models/WebSetting/MetaTag.php'))) {
      $keys = array_merge_recursive($keys, ['meta_tag']);
    }

    if (File::isFile(base_path('app/Models/WebSetting/WebPrivacy.php'))) {
      $keys = array_merge_recursive($keys, ['web_privacy']);
    }

    if (File::isFile(base_path('app/Models/WebSetting/WebSiteMap.php'))) {
      $keys = array_merge_recursive($keys, ['site_map']);
    }

    if (File::isFile(base_path('app/Filament/Admin/Pages/WebSetting/GoogleTags.php'))) {
      $keys = array_merge_recursive($keys, ['google_tags']);
    }

    if (File::isFile(base_path('app/Http/Controllers/MasterFunctionController.php'))) {
      $keys = array_merge_recursive($keys, ['web_translations']);
    }


    return $keys;
  }
}
