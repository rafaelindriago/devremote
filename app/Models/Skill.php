<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'level',
        'description',
    ];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class)
            ->withDefault();
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class)
            ->withDefault();
    }
}
