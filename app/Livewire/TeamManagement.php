<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class TeamManagement extends Component
{
    use WithFileUploads;

    // Store Creation
    public $storeName;

    public $storeDescription;

    public $image;

    // Add User to Store
    public $selectedUsers;

    public $selectedStore;

    // Change User Role
    public $selectedUser;

    public $selectedRole;

    // Data Collections
    public $stores;

    public $availableUsers;

    public $availableRoles;

    public function mount()
    {
        if (! Auth::user()->hasRole('super admin') && ! Auth::user()->hasRole('team admin')) {
            abort(403, 'Unauthorized access');
        }

        $this->loadData();
    }

    protected function loadData()
    {
        $this->stores = Store::with(['owner', 'users'])->where('tenant_id', Auth::user()->tenant_id)->get();

        // Get all users in the tenant
        $this->availableUsers = User::where('tenant_id', Auth::user()->tenant_id)->get();

        $this->availableRoles = Role::all();
    }

    public function createStore()
    {
        $this->validate([
            'storeName' => 'required|unique:stores,name',
            'storeDescription' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $logoPath = $this->image ? $this->image->store('logos', 'public') : null;

        Store::create([
            'name' => $this->storeName,
            'description' => $this->storeDescription,
            'logo' => $logoPath,
            'owner_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
        ]);

        session()->flash('status', 'Store created successfully!');
        $this->reset(['storeName', 'storeDescription', 'image']);
        $this->loadData();
    }

    public function deleteStore($storeId)
    {
        $store = Store::findOrFail($storeId);

        if ($store->owner_id !== Auth::id() && ! Auth::user()->hasRole('super admin')) {
            session()->flash('status', 'Unauthorized to delete this store.');

            return;
        }

        // Detach all users from store through pivot
        $store->users()->detach();
        $store->delete();

        session()->flash('status', 'Store '.$store->name.' deleted successfully!');
        $this->loadData();
    }

    public function addUserToStore()
    {
        $this->validate([
            'selectedUsers' => 'required|exists:users,id',
            'selectedStore' => 'required|exists:stores,id',
        ]);

        $user = User::where('tenant_id', Auth::user()->tenant_id)->findOrFail($this->selectedUsers);
        $store = Store::findOrFail($this->selectedStore);

        if ($store->users()->where('user_id', $user->id)->exists()) {
            session()->flash('status', 'User is already a member of this store.');

            return;
        }

        // Attach user to store through pivot
        $store->users()->attach($user->id);

        // Set current store if not set
        if (! $user->store_id) {
            $user->update(['store_id' => $store->id]);
        }

        session()->flash('status', 'User '.$user->name.' added to store '.$store->name);
        $this->loadData();
    }

    public function removeUserFromStore($userId, $storeId)
    {
        $user = User::findOrFail($userId);

        // Prevent non-super admins from removing a super admin from any store
        if ($user->hasRole('super admin') && ! Auth::user()->hasRole('super admin')) {
            session()->flash('status', 'Unauthorized: You cannot remove a super admin from the store.');

            return;
        }

        $store = Store::findOrFail($storeId);

        // Detach user from store through pivot
        $store->users()->detach($userId);

        // Reset current store if it was this store
        if ($user->store_id == $storeId) {
            $newStore = $user->stores()->first();
            $user->update(['store_id' => $newStore->id ?? null]);
        }

        session()->flash('status', 'User '.$user->name.' removed from store '.$store->name);
        $this->loadData();
    }

    public function changeUserRole()
    {
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
            'selectedRole' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($this->selectedUser);
        $user->syncRoles([$this->selectedRole]);

        session()->flash('status', 'Role changed to '.$this->selectedRole.' for '.$user->name);
        $this->reset(['selectedUser', 'selectedRole']);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.team-management');
    }
}
