<?php

namespace App\Livewire;

use Livewire\Component;

class Header extends Component
{
    public function logout()
    {
        $user = auth()->user();
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;
        $tenantSlug = tenancy()->initialized ? tenant('slug') : null;

        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        if ($isSuperAdmin) {
            return redirect()->route('super-admin.login');
        }

        if ($tenantSlug) {
            return redirect()->route('tenant.login', ['tenant' => $tenantSlug]);
        }

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.header');
    }
}
