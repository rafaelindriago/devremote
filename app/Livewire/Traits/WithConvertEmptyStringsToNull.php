<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait WithConvertEmptyStringsToNull
{
    public function updatedWithConvertEmptyStringsToNull(string $property, mixed $value): void
    {
        if (is_string($value) && trim($value) === '') {
            $this->fill([
                $property => null,
            ]);
        }
    }
}
