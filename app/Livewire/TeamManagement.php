<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\User;
use App\Models\Store;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class TeamManagement extends Component
{
    // Team Creation
    public $teamName;
    public $teamDescription;

    // Add User to Team
    public $selectedUsers;
    public $selectedTeam;

    // Change User Role
    public $selectedUser;
    public $selectedRole;

    // Data Collections
    public $teams;
    public $availableUsers;
    public $availableRoles;

    public function mount()
    {
        if (!Auth::user()->hasRole('super admin')) {
            abort(403, 'Unauthorized access');
        }

        $this->loadData();
    }

    protected function loadData()
    {
        $this->teams = Store::with(['owner', 'users'])->get();

        // Get users not in any team using pivot relationship
        $this->availableUsers = User::whereDoesntHave('teams')->get();

        // Get viewer role users
        $viewerUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'viewer');
        })->get();
        // dd($viewerUsers);
        $this->availableUsers = $this->availableUsers->merge($viewerUsers);
        $this->availableRoles = Role::all();
    }

    public function createTeam()
    {
        $this->validate([
            'teamName' => 'required|unique:teams,name',
            'teamDescription' => 'nullable|string|max:255'
        ]);

       Store::create([
            'name' => $this->teamName,
            'description' => $this->teamDescription,
            'owner_id' => Auth::id()
        ]);

        session()->flash('status', 'Team created successfully!');
        $this->reset(['teamName', 'teamDescription']);
        $this->loadData();
    }

    public function deleteTeam($teamId)
    {
        $team = Store::findOrFail($teamId);

        if ($team->owner_id !== Auth::id() && !Auth::user()->hasRole('super admin')) {
            session()->flash('status', 'Unauthorized to delete this team.');
            return;
        }

        // Detach all users from team through pivot
        $team->users()->detach();
        $team->delete();

        session()->flash('status', "Team " . $team->name . " deleted successfully!");
        $this->loadData();
    }

    public function addUserToTeam()
    {
        $this->validate([
            'selectedUsers' => 'required|exists:users,id',
            'selectedTeam' => 'required|exists:stores,id'
        ]);

        $user = User::findOrFail($this->selectedUsers)->where('tenant_id', Auth::user()->tenant_id);
        $store = Store::findOrFail($this->selectedTeam);

        if ($store->users()->where('user_id', $user->id)->exists()) {
            session()->flash('status', 'User is already a member of this store.');
            return;
        }

        // Attach user to team through pivot
        $store->users()->attach($user->id);

        // Set current team if not set
        if (!$user->store_id) {
            $user->update(['store_id' => $store->id]);
        }

        session()->flash('status', "User " . $user->name . " added to store " . $store->name);
        $this->loadData();
    }

    public function removeUserFromTeam($userId, $storeId)
    {
        $user = User::findOrFail($userId);
        $store = Store::findOrFail($storeId);

        // Detach user from team through pivot
        $store->users()->detach($userId);

        // Reset current team if it was this team
        if ($user->store_id == $storeId) {
            $newStore = $user->stores()->first();
            $user->update(['store_id' => $newStore->id ?? null]);
        }

        session()->flash('status', "User " . $user->name . " removed from store " . $store->name);
        $this->loadData();
    }

    public function changeUserRole()
    {
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
            'selectedRole' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($this->selectedUser);
        $user->syncRoles([$this->selectedRole]);

        session()->flash('status', "Role changed to " . $this->selectedRole . " for " . $user->name);
        $this->reset(['selectedUser', 'selectedRole']);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.team-management');
    }
}
