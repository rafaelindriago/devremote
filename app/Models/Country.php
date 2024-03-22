<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'code';

    protected $fillable = [
        'name',
        'data',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function developers()
    {
        return $this->hasMany(Developer::class, 'country_code', 'code');
    }
}
