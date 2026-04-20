<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Tenant;
use App\Mail\StoreLinkEmail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Layout;

class FindStore extends Component
{
    public $email = '';
    public $submitted = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    #[Layout('components.layouts.tenant-auth')]
    public function render()
    {
        return view('livewire.auth.find-store');
    }

    public function find()
    {
        $this->validate();

        // Find all users with this email
        $users = User::where('email', $this->email)->get();

        $stores = [];
        foreach ($users as $user) {
            // Find tenant associated with the user
            $tenant = Tenant::find($user->tenant_id);
            if ($tenant) {
                $stores[] = [
                    'name' => $tenant->name,
                    'url' => url('/' . $tenant->slug . '/login'),
                    'slug' => $tenant->slug
                ];
            }
        }

        // Remove duplicates if any
        $stores = array_map("unserialize", array_unique(array_map("serialize", $stores)));

        if (!empty($stores)) {
            // Send email
            Mail::to($this->email)->send(new StoreLinkEmail($stores));
        }

        $this->submitted = true;
    }
}
