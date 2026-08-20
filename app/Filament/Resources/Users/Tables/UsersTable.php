<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class UsersTable {
  public static function configure(Table $table): Table {
        return $table
      ->columns([
        TextColumn::make('id')
          ->label('ID')
          ->sortable(),
        ImageColumn::make('avatar_url')
          ->label(__('filament/resources/users.fields.avatar_url'))
          ->disk('root_folder')
          ->circular(),
        TextColumn::make('name')
          ->label(__('filament/resources/users.fields.name'))
          ->searchable()
          ->sortable(),
        TextColumn::make('email')
          ->label(__('filament/resources/users.fields.email'))
          ->searchable()
          ->sortable(),
        PhoneColumn::make('phone')
          ->label(__('filament/resources/users.fields.phone'))
          ->countryColumn('phone_country')
          ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
          ->toggleable(),
        TextColumn::make('roles.name')
          ->label(__('filament/resources/users.fields.roles'))
          ->badge()
          ->separator(','),
        IconColumn::make('is_primary_user')
          ->label(__('filament/resources/users.fields.is_primary_user'))
          ->boolean()
          ->getStateUsing(fn (User $record): bool => $record->isPrimaryUser()),
        TextColumn::make('created_at')
          ->label(__('filament/resources/users.fields.created_at'))
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        TextColumn::make('updated_at')
          ->label(__('filament/resources/users.fields.updated_at'))
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->deferFilters(false)
      ->persistFiltersInSession()
      ->persistSearchInSession()
      ->persistSortInSession()
      ->recordActions([
        EditAction::make()->iconButton(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->visible(fn (): bool => User::query()->exceptPrimary()->exists())
            ->action(function (Collection $records): void {
              $records
                ->reject(fn (User $record): bool => $record->isPrimaryUser())
                ->each
                ->delete();
            }),
        ]),
      ]);
  }
}
