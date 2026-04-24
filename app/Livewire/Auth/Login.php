<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth; // Ensure you import Auth

class Login extends Component
{
    public $email; // Add properties for email and password
    public $password;

    #[Layout('components.layouts.tenant-auth')]
    public function login() // Create a login method
    {
        $this->validate([ // Use $this->validate() for validation
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();

            // Handle Super Admin redirect
            if ($user->is_super_admin) {
                return redirect()->route('super-admin.dashboard');
            }

            // Handle Tenant redirect
            $tenant = \App\Models\Tenant::find($user->tenant_id);
            if ($tenant) {
                $isTenantBlocked = ! (bool) ($tenant->is_active ?? false)
                    || in_array(strtolower((string) ($tenant->status ?? '')), ['blocked', 'inactive', 'suspended'], true);

                if ($isTenantBlocked) {
                    Auth::logout();

                    return redirect()->back()->with('error', 'Your company access is currently blocked. Contact support.');
                }

                // If they are on a specific tenant login, but belong to another, redirect them to THEIR tenant
                // But usually, staying within the path-based intended URL is better if it exists.
                return redirect()->to('/' . $tenant->slug . '/admin')->with('status', 'Login successful!');
            }

            // Fallback for users without a specific tenant (e.g., global users)
            return redirect()->intended('/')->with('status', 'Login successful!');
        }

        // If login fails, redirect back with an error
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    /**
     * Handle logout request.
     */
    public function logout()
    {
        Auth::logout();
        session()->forget('current_team_id'); // Clear the current team ID
        return redirect('/')->with('status', 'Logged out successfully.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}