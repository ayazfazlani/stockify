<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;

class Dashboard extends Component
{

    #[\Livewire\Attributes\Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}