<?php

namespace App\FilamentHelpers\UploadFile;

use Filament\Forms\Components\FileUpload;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class WebpUploadFixedSize
{
    use UploadFileFunctionTrait;

    protected int $filter = 4;

    protected int $filterThumbnail = 4;

    protected int $width = 300;

    protected int $height = 300;

    protected int $quality = 90;

    protected string $canvas = '#ffffff';

    protected ?string $aspectRatio = null;

    protected bool $generateThumbnail = false;

    protected int $thumbWidth = 100;

    protected int $thumbHeight = 100;

    public static function make(): static
    {
        return new static();
    }

    public function setFilter(int $filter = 4): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setFilterThumbnail(int $filterThumbnail = 4): static
    {
        $this->filterThumbnail = $filterThumbnail;

        return $this;
    }

    public function setResize(int $width, int $height, int $quality = 90): static
    {
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;

        return $this;
    }

    public function setThumbnail(bool $value = true): static
    {
        $this->generateThumbnail = $value;

        return $this;
    }

    public function setAspectRatio(?string $value = null): static
    {
        $this->aspectRatio = $value;

        return $this;
    }

    public function setCanvas(?string $value = null): static
    {
        $this->canvas = $value ?? '#ffffff';

        return $this;
    }

    public function setThumbnailSize(int $width, int $height): static
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;

        return $this;
    }

    public function getColumns(): array
    {
        return [$this->fileUploadImages()];
    }

    public function fileUploadImages(): FileUpload
    {
        return FileUpload::make($this->fileName)
            ->label($this->getFileLabel())
            ->disk($this->diskDir)
            ->visibility($this->diskVisibility)
            ->directory($this->uploadDirectory)
            ->acceptedFileTypes(['image/*'])
            ->previewable($this->previewAble)
            ->hiddenLabel()
            ->multiple($this->multipleFiles)
            ->image()
            ->columnSpanFull()
            ->imageEditor()
            ->downloadable()
            ->deletable()
            ->reorderable()
            ->dehydrated()
            ->deleteUploadedFileUsing(fn (string $file, mixed $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn ($file, $record, $livewire) => $this->handleUploadFixedSize($file, $record, $livewire))
            ->imageCropAspectRatio($this->handleAspectRatioFixedSize())
            ->required($this->requiredUpload);
    }

    protected function handleAspectRatioFixedSize(): ?string
    {
        if (! $this->aspectRatio || ! $this->width || ! $this->height) {
            return null;
        }

        if ($this->aspectRatio !== 'system') {
            return $this->aspectRatio;
        }

        $gcd = $this->gcd($this->width, $this->height);

        return ($this->width / $gcd).':'.($this->height / $gcd);
    }

    protected function gcd(int $a, int $b): int
    {
        return $b === 0 ? $a : $this->gcd($b, $a % $b);
    }

    protected function handleUploadFixedSize($file, $record, $livewire = null): string
    {
        $realPath = $file->getRealPath();

        if (! $realPath || ! file_exists($realPath)) {
            throw new \RuntimeException('Temporary upload file was not found.');
        }

        $manager = new ImageManager(new GdDriver());
        $basePath = $this->uploadDirectory.'/'.now()->format('Y-m');
        $this->ensureDirectoryExists($basePath);

        $filenameBase = $this->resolveFilename($record, $livewire);
        $newPath = $basePath.'/'.$filenameBase.'.webp';

        $this->processImageFixedSize($manager, $realPath, $newPath, [
            'type' => $this->filter,
            'width' => $this->width,
            'height' => $this->height,
            'quality' => $this->quality,
        ]);

        if ($record) {
            $record->{$this->getThumbnailFieldName()} = null;
        }

        if ($this->generateThumbnail) {
            $thumbnailPath = $basePath.'/'.$filenameBase.'_thumb.webp';

            $this->processImageFixedSize($manager, $realPath, $thumbnailPath, [
                'type' => $this->filterThumbnail,
                'width' => $this->thumbWidth,
                'height' => $this->thumbHeight,
                'quality' => $this->quality,
            ]);

            if ($record) {
                $record->{$this->getThumbnailFieldName()} = $thumbnailPath;
            }
        }

        return $newPath;
    }

    protected function processImageFixedSize(ImageManager $manager, string $realPath, string $savePath, array $data = []): void
    {
        $type = $data['type'] ?? 1;
        $width = $data['width'] ?? 300;
        $height = $data['height'] ?? 300;
        $canvas = $data['canvas'] ?? $this->canvas;
        $quality = $data['quality'] ?? 85;

        $image = $manager->read($realPath);

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
