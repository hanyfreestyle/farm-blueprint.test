<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'sort_order',
    ];

    protected $attributes = [
        'sort_order' => 0,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'section_id')->orderBy('sort_order')->orderBy('id');
    }

    public function scopeMainSections(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubsections(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }
}
