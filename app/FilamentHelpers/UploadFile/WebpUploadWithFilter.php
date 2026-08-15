<?php

namespace App\FilamentHelpers\UploadFile;

use App\Models\WebSetting\UploadFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class WebpUploadWithFilter
{
    use UploadFileFunctionTrait;

    public static function make(): static
    {
        return new static();
    }

    public function getColumns(): array
    {
        $filterId = $this->filterId ?: 0;
        $filter = UploadFilter::query()->with('sizes')->withCount('sizes')->find($filterId);

        if (! $filter) {
            return [
                Placeholder::make($this->fileName.'_missing_filter')
                    ->content(view('components.admin.settings.missing-filter')),
            ];
        }

        $columns = [
            $this->fileUploadImages($filter, $filterId),
            Placeholder::make($this->fileName.'_filter_notes')
                ->live()
                ->content(view('components.admin.settings.print_notes', compact('filter'))),
            $this->HiddenInputFiled($filterId),
        ];

        if ($this->changeFilter) {
            $columns[] = $this->SelectFiled($filterId);
        } elseif ($this->canChangeFilter) {
            $columns[] = Placeholder::make($this->fileName.'_change_filter')
                ->live()
                ->content(view('components.admin.settings.change-filter', compact('filter')));
        }

        return $columns;
    }

    public function fileUploadImages(UploadFilter $filter, int $filterId = 0): FileUpload
    {
        return FileUpload::make($this->fileName)
            ->label($this->getFileLabel())
            ->disk($this->diskDir)
            ->visibility($this->diskVisibility)
            ->directory($this->uploadDirectory)
            ->acceptedFileTypes(['image/*'])
            ->hiddenLabel()
            ->image()
            ->imageEditor()
            ->downloadable()
            ->multiple($this->multipleFiles)
            ->previewable($this->previewAble)
            ->deletable()
            ->reorderable()
            ->dehydrated()
            ->deleteUploadedFileUsing(fn (string $file, mixed $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn ($file, $record, $livewire) => $this->handleUpload($file, $record, $livewire, $filter, $filterId))
            ->required($this->requiredUpload)
            ->imageCropAspectRatio($this->handleAspectRatio($filter));
    }

    protected function handleUpload($file, $record, $livewire, UploadFilter $filter, int $filterId): string
    {
        if ($this->changeFilter) {
            $selectedFilterId = data_get($livewire->data, $this->getHiddenFieldName($filterId));
            $filter = UploadFilter::query()->with('sizes')->findOrFail($selectedFilterId);
        }

        $realPath = $file->getRealPath();

        if (! $realPath || ! file_exists($realPath)) {
            throw new \RuntimeException('Temporary upload file was not found.');
        }

        $manager = new ImageManager(new GdDriver());
        $basePath = $this->uploadDirectory.'/'.now()->format('Y-m');
        $this->ensureDirectoryExists($basePath);

        $filenameBase = $this->resolveFilename($record, $livewire);
        $newPath = $basePath.'/'.$filenameBase.'.webp';
        $thumbnailPath = $basePath.'/'.$filenameBase.'_thumb.webp';

        $this->processImage($manager, $realPath, $newPath, $filter);

        if ($record) {
            $record->{$this->getThumbnailFieldName()} = null;
        }

        $firstSize = $filter->sizes->first();

        if ($firstSize && ! $this->multipleFiles) {
            $this->processImage($manager, $realPath, $thumbnailPath, $filter, $firstSize);

            if ($record) {
                $record->{$this->getThumbnailFieldName()} = $thumbnailPath;
            }
        }

        return $newPath;
    }

    protected function processImage(ImageManager $manager, string $realPath, string $savePath, UploadFilter $filter, ?object $firstSize = null): void
    {
        $type = $firstSize->type ?? $filter->type ?? 1;
        $width = $firstSize->width ?? $filter->width ?? 300;
        $height = $firstSize->height ?? $filter->height ?? 300;
        $canvas = $firstSize->canvas_back ?? $filter->canvas_back ?? '#ffffff';
        $quality = $filter->quality_val ?? 90;

        $image = $manager->read($realPath);

        if ($filter->greyscale) {
            $image->greyscale();
        }

        if ($filter->blur && $filter->blur_size > 0) {
            $image->blur($filter->blur_size);
        }

        if ($filter->pixelate && $filter->pixelate_size > 0) {
            $image->pixelate($filter->pixelate_size);
        }

        if ($filter->flip_state) {
            $image->flop();
        }

        if ($filter->flip_v) {
            $image->flip();
        }

        match ($type) {
            2 => $image->scaleDown(width: $width),
            3 => $image->scaleDown(height: $height),
            4 => $image->cover($width, $height),
            5 => $image->contain($width, $height, $canvas),
            default => null,
        };

        $image
            ->encode(new WebpEncoder($quality))
            ->save(\Storage::disk($this->diskDir)->path($savePath));
    }
}
