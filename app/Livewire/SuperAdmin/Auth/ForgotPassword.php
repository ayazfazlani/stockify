<?php

namespace App\Livewire\SuperAdmin\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email = '';
    public $emailSent = false;
    public $error = '';

    protected $rules = [
        'email' => 'required|email'
    ];

    public function sendResetLink()
    {
        $this->validate();

        $user = \App\Models\User::where('email', $this->email)
            ->where('is_super_admin', true)
            ->first();

        if (!$user) {
            $this->error = 'We cannot find a super admin user with that email address.';
            return;
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
            $this->error = '';
        } else {
            $this->error = 'Unable to send password reset link. Please try again.';
        }
    }

    #[Layout('components.layouts.super-admin-auth')]
    public function render()
    {
        return view('livewire.super-admin.auth.forgot-password');
    }
}