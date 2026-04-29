<?php

namespace App\Livewire\Web;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Home extends Component
{
    #[Layout('components.layouts.web')]
    public function render()
    {
        $plans = \App\Models\Plan::where('active', true)->orderBy('sort_order')->get();
        return view('welcome', compact('plans'));
    }
}
