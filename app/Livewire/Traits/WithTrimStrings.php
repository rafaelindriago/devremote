<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait WithTrimStrings
{
    public function updatedWithTrimStrings(string $property, mixed $value): void
    {
        $trimExcepts = method_exists($this, 'trimExcepts')
            ? $this->trimExcepts()
            : (property_exists($this, 'trimExcepts')
                ? $this->trimExcepts
                : []);

        foreach ($trimExcepts as $trimExcept) {
            $trimExceptPattern = Str::of("/^{$trimExcept}$/")
                ->replace('.', '\.')
                ->replace('*', '.+');

            if (Str::of($property)->test($trimExceptPattern)) {
                return;
            }
        }

        $this->fill([
            $property => $value instanceof Stringable
                ? $value->trim()
                : (is_string($value)
                    ? trim($value)
                    : $value),
        ]);
    }
}
