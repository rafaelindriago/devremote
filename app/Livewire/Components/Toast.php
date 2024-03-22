<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Toast extends Component
{
    public array $toasts = [];

    public function diffForHumans(int $timestamp): string
    {
        return Carbon::createFromTimestamp($timestamp)
            ->diffForHumans([
                'options' => Carbon::JUST_NOW,
            ]);
    }

    public function render(): View
    {
        return view('livewire.components.toast')
            ->with('diffForHumans', [$this, 'diffForHumans']);
    }

    #[On('livewire-toast-show')]
    public function show(string $type, string $message): void
    {
        $this->toasts[] = [
            'type' => $type,
            'message' => $message,
            'timestamp' => Carbon::now()->timestamp,
        ];
    }

    public function dismiss(int $key): void
    {
        Arr::forget($this->toasts, $key);
    }
}
