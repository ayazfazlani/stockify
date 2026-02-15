<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Users extends Component
{
     #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.users');
    }
}
