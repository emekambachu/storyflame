<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUuids, HasSchemalessAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'attributes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    /**
     * Get the stories for the user.
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class);
    }

    public function favoriteMovies(): MorphToMany
    {
        return $this->morphedByMany(
            Media::class,
            'favorite',
            'user_favorites',
        );
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }
}
