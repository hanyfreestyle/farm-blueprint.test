<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource {
  protected static ?string $model = User::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

  public static function form(Schema $schema): Schema {
    return UserForm::configure($schema);
  }

  public static function table(Table $table): Table {
    return UsersTable::configure($table);
  }

  public static function getRelations(): array {
    return [
      //
    ];
  }

  public static function getPages(): array {
    return [
      'index' => ListUsers::route('/'),
      'create' => CreateUser::route('/create'),
      'edit' => EditUser::route('/{record}/edit'),
    ];
  }

  public static function canDelete($record): bool {
    return !$record->isPrimaryUser();
  }

  public static function canDeleteAny(): bool {
    return true;
  }

  public static function canAccess(): bool {
    $user = auth()->user();

    if ($user instanceof User && $user->isPrimaryUser()) {
      return true;
    }

    return parent::canAccess();
  }

  public static function getNavigationLabel(): string {
    return __('filament/resources/users.navigation_label');
  }

  public static function getModelLabel(): string {
    return __('filament/resources/users.model_label');
  }

  public static function getPluralModelLabel(): string {
    return __('filament/resources/users.plural_model_label');
  }

  public static function getNavigationGroup(): ?string {
    return __('filament/resources/users.navigation_group');
  }
}
