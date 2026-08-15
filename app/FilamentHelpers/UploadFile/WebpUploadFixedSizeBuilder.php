<?php

namespace App\FilamentHelpers\UploadFile;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class WebpUploadFixedSizeBuilder extends FileUpload
{
    protected int $filter = 4;

    protected int $width = 300;

    protected int $height = 300;

    protected int $quality = 90;

    protected bool $generateThumbnail = false;

    protected int $thumbWidth = 100;

    protected int $thumbHeight = 100;

    protected string $diskDir = 'root_folder';

    protected string $diskVisibility = 'public';

    protected string $uploadDirectory = 'uploads-site';

    protected string $thumbnailSuffix = '_thumbnail';

    public function setFilter(int $filter = 4): static
    {
        $this->filter = $filter;

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

    public function setThumbnailSize(int $width, int $height): static
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;

        return $this;
    }

    public function setUploadDirectory(string $dir): static
    {
        $this->uploadDirectory = $dir;

        return $this;
    }

    public function setDiskDir(string $diskDir): static
    {
        $this->diskDir = $diskDir;

        return $this;
    }

    public function setDiskVisibility(string $visibility): static
    {
        $this->diskVisibility = $visibility;

        return $this;
    }

    public function setRequiredUpload(bool $value = true): static
    {
        if ($value) {
            $this->required();
        }

        return $this;
    }

    public function setThumbnailSuffix(string $suffix = '_thumbnail'): static
    {
        $this->thumbnailSuffix = $suffix;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->disk($this->diskDir)
            ->visibility($this->diskVisibility)
            ->directory($this->uploadDirectory)
            ->acceptedFileTypes(['image/*'])
            ->image()
            ->imageEditor()
            ->downloadable()
            ->deletable()
            ->reorderable()
            ->dehydrated()
            ->deleteUploadedFileUsing(fn (string $file, mixed $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn ($file, $record, $livewire) => $this->handleUploadFixedSize($file, $record, $livewire));
    }

    protected function handleFileDeletion(string $file, mixed $record): void
    {
        Storage::disk($this->diskDir)->delete($file);

        if (! $record) {
            return;
        }

        $thumbnailField = $this->getFieldName().$this->thumbnailSuffix;
        $data = (array) ($record->data ?? []);

        if (filled($data[$thumbnailField] ?? null)) {
            Storage::disk($this->diskDir)->delete($data[$thumbnailField]);
            $data[$thumbnailField] = null;
        }

        $data[$this->getFieldName()] = null;
        $record->data = $data;
        $record->save();
    }

    protected function ensureDirectoryExists(string $basePath): void
    {
        Storage::disk($this->diskDir)->makeDirectory($basePath);
    }

    protected function resolveFilename(): string
    {
        return Str::slug(uniqid('img-', true)) ?: 'img';
    }

    protected function getFieldName(): string
    {
        $parts = explode('.', $this->getStatePath());

        return (string) end($parts);
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

        $filenameBase = $this->resolveFilename();
        $newPath = $basePath.'/'.$filenameBase.'.webp';

        $this->processImageFixedSize($manager, $realPath, $newPath, [
            'type' => $this->filter,
            'width' => $this->width,
            'height' => $this->height,
            'quality' => $this->quality,
        ]);

        if ($this->generateThumbnail) {
            $thumbnailPath = $basePath.'/'.$filenameBase.'_thumb.webp';

            $this->processImageFixedSize($manager, $realPath, $thumbnailPath, [
                'type' => $this->filter,
                'width' => $this->thumbWidth,
                'height' => $this->thumbHeight,
                'quality' => $this->quality,
            ]);

            if ($livewire) {
                $thumbnailField = $this->getFieldName().$this->thumbnailSuffix;
                data_set($livewire, $this->resolveThumbnailStatePath($thumbnailField), $thumbnailPath);
            }
        }

        return $newPath;
    }

    protected function resolveThumbnailStatePath(string $thumbnailField): string
    {
        $parts = explode('.', $this->getStatePath());
        array_pop($parts);
        $parts[] = $thumbnailField;

        return implode('.', $parts);
    }

    protected function processImageFixedSize(ImageManager $manager, string $realPath, string $savePath, array $data = []): void
    {
        $type = $data['type'] ?? 1;
        $width = $data['width'] ?? 300;
        $height = $data['height'] ?? 300;
        $canvas = $data['canvas'] ?? '#ffffff';
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
            ->save(Storage::disk($this->diskDir)->path($savePath));
    }
}
