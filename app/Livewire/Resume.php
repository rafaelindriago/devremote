<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Resume extends Component
{
    public function render(): View
    {
        return view('livewire.resume');
    }
}
