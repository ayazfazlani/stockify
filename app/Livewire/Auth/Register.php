<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|max:255|unique:users,email')]
    public $email = '';

    #[Rule('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    #[Layout('components.layouts.web')]
    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Assign 'customer' role. Create if it doesn't exist.
        Role::firstOrCreate(['name' => 'customer']);
        $user->assignRole('customer');

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('marketplace.index');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
