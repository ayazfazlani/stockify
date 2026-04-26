<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class ManageRoles extends Component
{
    public $user;
    public $allRoles = [];
    public $selectedRole;
    public $newRoleName;

    public $permissions = [];
    public $selectedPermissions = [];
    
    public function mount($userId)
    {
        $this->user = User::find($userId);

        if (!$this->user) {
            session()->flash('error', 'User not found.');
            return redirect()->route('home');
        }

        $this->loadRolesAndPermissions();
    }

    protected function loadRolesAndPermissions()
    {
        $currentTeam = Auth::user()->currentTeam;
        
        // Get roles based on team context
        if (Auth::user()->isSuperAdmin()) {
            $this->allRoles = Role::all()->pluck('name')->toArray();
        } else {
            $this->allRoles = Role::where(function($query) use ($currentTeam) {
                $query->whereNull('team_id')
                      ->orWhere('team_id', $currentTeam->id);
            })->pluck('name')->toArray();
        }

        // Get all available permissions
        $this->permissions = Permission::all()->pluck('name')->toArray();
    }

    public function addRole()
    {
        if (!$this->selectedRole) {
            return;
        }

        // Check if user is authorized to assign this role
        if (!Auth::user()->can('manage roles')) {
            session()->flash('error', 'You are not authorized to assign roles.');
            return;
        }

        $this->user->assignRole($this->selectedRole);
        $this->selectedRole = '';
        session()->flash('success', 'Role assigned successfully.');
    }

    public function removeRole($roleName)
    {
        // Check if user is authorized to remove roles
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->hasRole('team admin')) {
            session()->flash('error', 'You are not authorized to remove roles.');
            return;
        }

        // Prevent removing the last team admin
        if ($roleName === 'team admin') {
            $teamAdminCount = User::role('team admin')
                ->where('current_team_id', Auth::user()->current_team_id)
                ->count();
            
            if ($teamAdminCount <= 1 && $this->user->id === Auth::user()->current_team_id) {
                session()->flash('error', 'Cannot remove the last team admin.');
                return;
            }
        }

        $this->user->removeRole($roleName);
        session()->flash('success', 'Role removed successfully.');
    }

    public function createRole()
    {
        if (!$this->newRoleName) {
            return;
        }

        $tenant = tenant();
        if (!$tenant->hasFeature('custom-roles') && !Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Your current plan does not support custom roles. Please upgrade.');
            return;
        }

        // Only super admin and team admin can create roles
        if (!Auth::user()->can('manage roles')) {
            session()->flash('error', 'You are not authorized to create roles.');
            return;
        }

        // Create role with team context if not super admin
        $roleData = [
            'name' => $this->newRoleName,
            'guard_name' => 'web',
            'team_id' => !Auth::user()->isSuperAdmin() ? $tenant->id : null,
        ];

        $role = Role::create($roleData);
        
        // Sync selected permissions
        if (!empty($this->selectedPermissions)) {
            $role->syncPermissions($this->selectedPermissions);
        }

        $this->newRoleName = '';
        $this->selectedPermissions = [];
        $this->loadRolesAndPermissions();
        
        session()->flash('success', 'Role created successfully with ' . count($this->selectedPermissions) . ' permissions.');
    }

    public function deleteCustomRole($roleId)
    {
        $role = Role::findOrFail($roleId);

        // Prevent deleting system roles
        $protectedRoles = ['super admin', 'team admin', 'team manager', 'team member', 'viewer'];
        if (in_array($role->name, $protectedRoles) && $role->team_id === null) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }

        if (!Auth::user()->can('manage roles')) {
            session()->flash('error', 'Unauthorized.');
            return;
        }

        $role->delete();
        $this->loadRolesAndPermissions();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        return view('livewire.manage-roles', [
            'userRoles' => $this->user->roles->pluck('name')->toArray()
        ]);
    }
}
