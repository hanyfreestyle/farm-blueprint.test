<?php

namespace App\FilamentHelpers\UploadFile;

use App\Models\WebSetting\UploadFilter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadFileFunctionTrait
{
    protected int $filterId = 0;

    protected string $diskDir = 'root_folder';

    protected string $diskVisibility = 'public';

    protected string $uploadDirectory = 'uploads';

    protected string $fileName = 'photo';

    protected bool $changeFilter = false;

    protected bool $requiredUpload = false;

    protected bool $multipleFiles = false;

    protected bool $previewAble = true;

    protected bool $canChangeFilter = false;

    protected ?string $renameTo = null;

    protected ?string $renameFromDb = null;

    protected ?string $setRenameFromInput = null;

    protected ?string $fileLabel = null;

    public function setFilterId(int $filterId): static
    {
        $this->filterId = $filterId;

        return $this;
    }

    public function setRenameTo(?string $renameTo): static
    {
        $this->renameTo = $renameTo;

        return $this;
    }

    public function setRenameFromDb(?string $renameFromDb): static
    {
        $this->renameFromDb = $renameFromDb;

        return $this;
    }

    public function setRenameFromInput(?string $setRenameFromInput): static
    {
        $this->setRenameFromInput = $setRenameFromInput;

        return $this;
    }

    public function setCanChangeFilter(bool $canChangeFilter): static
    {
        $this->canChangeFilter = $canChangeFilter;

        return $this;
    }

    public function setRequiredUpload(bool $requiredUpload): static
    {
        $this->requiredUpload = $requiredUpload;

        return $this;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setFileLabel(?string $fileLabel): static
    {
        $this->fileLabel = $fileLabel;

        return $this;
    }

    public function setChangeFilter(bool $changeFilter): static
    {
        $this->changeFilter = $changeFilter;

        return $this;
    }

    public function setMultipleFiles(bool $multipleFiles): static
    {
        $this->multipleFiles = $multipleFiles;

        if ($this->multipleFiles) {
            $this->previewAble = false;
        }

        return $this;
    }

    public function setDiskDir(string $diskDir): static
    {
        $this->diskDir = $diskDir;

        return $this;
    }

    public function setDiskVisibility(string $diskVisibility): static
    {
        $this->diskVisibility = $diskVisibility;

        return $this;
    }

    public function setUploadDirectory(string $directory): static
    {
        $this->uploadDirectory = $directory;

        return $this;
    }

    protected function handleFileDeletion(string $file, mixed $record): void
    {
        Storage::disk($this->diskDir)->delete($file);

        if (! $record instanceof Model) {
            return;
        }

        $thumbnailField = $this->getThumbnailFieldName();
        $thumbnailPath = data_get($record, $thumbnailField);

        if (filled($thumbnailPath)) {
            Storage::disk($this->diskDir)->delete($thumbnailPath);
            $record->setAttribute($thumbnailField, null);
        }

        $record->setAttribute($this->fileName, null);
        $record->save();
    }

    protected function resolveFilename(mixed $record = null, mixed $livewire = null): string
    {
        $base = $this->renameTo ?: 'img';

        if ($this->setRenameFromInput && filled($inputValue = data_get($livewire, 'data.'.$this->setRenameFromInput))) {
            $base = $inputValue;
        } elseif ($this->renameFromDb && filled($dbValue = data_get($record, $this->renameFromDb))) {
            $base = $dbValue;
        }

        $base = Str::limit($this->slugify($base), 80, '');

        return Str::limit(uniqid($base.'-', true), 100, '');
    }

    protected function ensureDirectoryExists(string $basePath): void
    {
        Storage::disk($this->diskDir)->makeDirectory($basePath);
    }

    protected function handleAspectRatio(mixed $filter): ?string
    {
        return filled($filter?->crop_aspect_ratio) ? $filter->crop_aspect_ratio : null;
    }

    protected function sanitizeFileName(string $name): string
    {
        return Str::limit($this->slugify($name), 100, '');
    }

    public function SelectFiled(int $filterId): Select
    {
        $fieldName = $this->getSelectFieldName($filterId);
        $hiddenFieldName = $this->getHiddenFieldName($filterId);

        return Select::make($fieldName)
            ->label('فلتر الرفع')
            ->options(fn (): array => UploadFilter::getUploadFilterCacheList()->pluck('name', 'id')->all())
            ->afterStateHydrated(function (Set $set, mixed $state) use ($fieldName, $filterId): void {
                if (blank($state)) {
                    $set($fieldName, $filterId);
                }
            })
            ->afterStateUpdated(fn (Set $set, mixed $state): mixed => $set($hiddenFieldName, $state))
            ->default($filterId)
            ->live()
            ->required()
            ->searchable()
            ->preload()
            ->dehydrated(false);
    }

    public function HiddenInputFiled(int $filterId): Hidden
    {
        $fieldName = $this->getHiddenFieldName($filterId);

        return Hidden::make($fieldName)
            ->afterStateHydrated(function (Set $set, mixed $state) use ($fieldName, $filterId): void {
                if (blank($state)) {
                    $set($fieldName, $filterId);
                }
            });
    }

    protected function getFileLabel(): string
    {
        return $this->fileLabel ?? 'Photo';
    }

    protected function getThumbnailFieldName(): string
    {
        return $this->fileName.'_thumbnail';
    }

    protected function getHiddenFieldName(int $filterId): string
    {
        return "{$this->fileName}_hidden_filter_id_{$filterId}";
    }

    protected function getSelectFieldName(int $filterId): string
    {
        return "{$this->fileName}_upload_filter_{$filterId}";
    }

    protected function slugify(string $value): string
    {
        return Url_Slug($value) ?: 'file';
    }
}
