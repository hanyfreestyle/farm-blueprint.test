<?php

namespace App\Filament\Resources\UploadFilters\Pages;

use App\Filament\Resources\UploadFilters\UploadFilterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListUploadFilters extends ListRecords
{
    protected static string $resource = UploadFilterResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
