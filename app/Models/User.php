<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasTranslations;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_country',
        'avatar_url',
        'avatar_url_thumbnail',
        'social',
        'slug',
        'author_name',
        'job_title',
        'des',
        'short_des',
        'g_h1',
        'g_title',
        'g_des',
    ];

    public array $translatable = [
        'slug',
        'author_name',
        'job_title',
        'des',
        'short_des',
        'g_h1',
        'g_title',
        'g_des',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social' => 'array',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin';
    }

    public function isPrimaryUser(): bool
    {
        return $this->getKey() === static::query()->min($this->getKeyName());
    }

    public function ensurePrimaryUserRole(): void
    {
        if (! $this->isPrimaryUser()) {
            return;
        }

        $this->syncRoles(['super_admin']);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (blank($this->avatar_url)) {
            return null;
        }

        return Storage::disk('root_folder')->url($this->avatar_url);
    }

    public function scopeExceptPrimary(Builder $query): Builder
    {
        $primaryUserId = static::query()->min($this->getKeyName());

        if ($primaryUserId === null) {
            return $query;
        }

        return $query->whereKeyNot($primaryUserId);
    }
}
