<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\InviteUserMail;
use App\Models\InvitationToken;
use Illuminate\Support\Facades\Mail;

class InviteUser extends Component
{
    public $email;

    public function sendInvitation()
    {
        // Check if the email is already registered
        if (User::where('email', $this->email)->exists()) {
            session()->flash('error', 'Email already registered.');
            return;
        }

        $invitation = InvitationToken::where('expires_at', '>', now())
            ->first();
        if ($invitation) {
            $invitation->delete();
        }
        // Generate a unique invitation token
        $token = Str::random(32);

        // Store the token in the database
        InvitationToken::create([
            'email' => $this->email,
            'token' => $token,
            'tenant_id' => auth()->user()?->tenant_id,
            'expires_at' => now()->addHours(24), // Token valid for 24 hours
        ]);

        // Send the invitation email
        $tenantSlug = tenant('slug');
        if (! $tenantSlug && auth()->user()?->tenant_id) {
            $tenant = \App\Models\Tenant::find(auth()->user()->tenant_id);
            $tenantSlug = $tenant ? $tenant->slug : null;
        }
        
        Mail::to($this->email)->send(new InviteUserMail($token, $tenantSlug));

        // Show a success message
        session()->flash('message', 'Invitation sent successfully!');
    }

    public function render()
    {
        return view('livewire.invite-user');
    }
}
