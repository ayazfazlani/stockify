<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Support extends Component
{
    #[\Livewire\Attributes\Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.support');
    }
}
