<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Avatar extends Component
{
    #[On('livewire-photo-file-stored')]
    #[On('livewire-photo-file-deleted')]
    public function render(): View
    {
        return view('livewire.components.avatar');
    }
}
