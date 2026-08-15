<?php

namespace App\Filament\Resources\UploadFilters\Pages;

use App\Filament\Resources\UploadFilters\UploadFilterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditUploadFilter extends EditRecord
{
    protected static string $resource = UploadFilterResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
