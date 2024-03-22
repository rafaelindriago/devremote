<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsDate;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'birth_at',
        'email',
        'phone',
    ];

    protected $casts = [
        'birth_at' => AsDate::class,
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code')
            ->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault();
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
