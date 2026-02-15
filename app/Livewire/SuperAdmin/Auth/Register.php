<?php

namespace App\Livewire\SuperAdmin\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        // Check if this is the first user being registered
        $userCount = User::count();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            // Make the first registered user a super admin automatically
            'is_super_admin' => $userCount === 0
        ]);

        if ($userCount > 0 && !Auth::user()?->is_super_admin) {
            session()->flash('error', 'Only existing super admins can register new super admin users.');
            return redirect()->route('super-admin.login');
        }

        Auth::login($user);

        session()->flash('success', 'Successfully registered!');
        return redirect()->route('super-admin.dashboard');
    }

        #[Layout('components.layouts.super-admin-auth')]
    public function render()
    {
        return view('livewire.super-admin.auth.register');
    }
}