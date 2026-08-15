<?php

namespace App\Models\WebSetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class UploadFilter extends Model
{
    protected $table = 'config_upload_filter';

    protected $fillable = [
        'name',
        'cat_id',
        'type',
        'convert_state',
        'quality_val',
        'width',
        'height',
        'crop_aspect_ratio',
        'canvas_back',
        'greyscale',
        'blur',
        'blur_size',
        'pixelate',
        'pixelate_size',
        'flip_state',
        'flip_v',
        'text_state',
        'text_print',
        'font_size',
        'font_path',
        'font_color',
        'font_opacity',
        'text_position',
        'watermark_state',
        'watermark_img',
        'watermark_position',
        'state',
        'notes',
        'is_notes',
    ];

    protected $attributes = [
        'type' => 1,
        'convert_state' => true,
        'quality_val' => 85,
        'canvas_back' => '#ffffff',
        'greyscale' => false,
        'blur' => false,
        'blur_size' => 0,
        'pixelate' => false,
        'pixelate_size' => 5,
        'flip_state' => false,
        'flip_v' => false,
        'text_state' => false,
        'watermark_state' => false,
        'state' => 0,
        'is_notes' => false,
    ];

    protected function casts(): array
    {
        return [
            'convert_state' => 'boolean',
            'greyscale' => 'boolean',
            'blur' => 'boolean',
            'pixelate' => 'boolean',
            'flip_state' => 'boolean',
            'flip_v' => 'boolean',
            'text_state' => 'boolean',
            'watermark_state' => 'boolean',
            'is_notes' => 'boolean',
            'notes' => 'array',
        ];
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(UploadFilterSize::class, 'filter_id')->orderBy('id');
    }

    public static function getUploadFilterCacheList()
    {
        return Cache::rememberForever('upload_filters.list', fn () => static::query()->orderBy('name')->get(['id', 'name']));
    }
}
