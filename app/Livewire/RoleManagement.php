<?php

namespace App\Livewire;

use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagement extends Component
{
    public $roles;

    public $allPermissions;

    public $showRoleModal = false;

    public $isEditing = false;

    public $roleId = null;

    public $roleName = '';

    public $selectedPermissions = [];

    public function mount()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to role management.');
        }

        $this->allPermissions = PermissionService::getPermissions();
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::whereIn('tenant_id', [null, Auth::user()->tenant_id])
            ->with('permissions')
            ->get();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['roleId', 'roleName', 'selectedPermissions', 'isEditing']);
        $this->showRoleModal = true;
    }

    public function editRole($id)
    {
        $role = Role::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $this->resetValidation();
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showRoleModal = true;
    }

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,'.($this->roleId ?? 'NULL').',id,tenant_id,'.Auth::user()->tenant_id,
            'selectedPermissions' => 'required|array|min:1',
        ]);

        if ($this->isEditing) {
            $role = Role::where('tenant_id', Auth::user()->tenant_id)->findOrFail($this->roleId);
            $role->update(['name' => $this->roleName]);
        } else {
            $role = Role::create([
                'name' => $this->roleName,
                'tenant_id' => Auth::user()->tenant_id,
                'guard_name' => 'web',
            ]);
        }

        // Ensure all selected permissions exist globally
        foreach ($this->selectedPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('status', $this->isEditing ? 'Role updated successfully!' : 'Role created successfully!');
        $this->showRoleModal = false;
        $this->loadRoles();
    }

    public function deleteRole($id)
    {
        $role = Role::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        // Prevent deleting roles that are in use
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role as it is currently assigned to users.');

            return;
        }

        $role->delete();
        session()->flash('status', 'Role deleted successfully!');
        $this->loadRoles();
    }

    public function render()
    {
        return view('livewire.role-management');
    }
}
