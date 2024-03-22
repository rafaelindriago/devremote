<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'data',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
