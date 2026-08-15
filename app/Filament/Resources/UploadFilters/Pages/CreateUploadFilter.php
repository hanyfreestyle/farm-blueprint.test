<?php

namespace App\Filament\Resources\UploadFilters\Pages;

use App\Filament\Resources\UploadFilters\UploadFilterResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateUploadFilter extends CreateRecord
{
    protected static string $resource = UploadFilterResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
