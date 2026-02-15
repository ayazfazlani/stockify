<?php

namespace App\Livewire\SuperAdmin\Auth;


use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $error = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_super_admin' => true], $this->remember)) {
            return redirect()->route('super-admin.dashboard');
        }

        $this->error = 'Invalid credentials or insufficient permissions.';
    }

   public function redirectToGoogle()
    {
        // Use Livewire's redirect method to external URL
        return $this->redirect(route('super-admin.google.redirect'), navigate: true);
    }

    #[Layout('components.layouts.super-admin-auth')]
    public function render()
    {
      
        return view('livewire.super-admin.auth.login');
    }
}