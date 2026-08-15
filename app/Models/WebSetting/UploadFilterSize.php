<?php

namespace App\Models\WebSetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadFilterSize extends Model
{
    public $timestamps = false;

    protected $table = 'config_upload_filter_sizes';

    protected $fillable = [
        'filter_id',
        'name',
        'type',
        'width',
        'height',
        'canvas_back',
        'text_state',
        'watermark_state',
    ];

    public function uploadFilter(): BelongsTo
    {
        return $this->belongsTo(UploadFilter::class, 'filter_id');
    }
}
