<div data-stockify>
    <div class="p-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="sf-card mb-6">
            <div class="p-6">
                <h1 class="sf-page-title">
                    <i class='bx bx-group mr-2' style="color: #4361EE;"></i>
                    User Management
                </h1>
                <p class="sf-page-subtitle mt-1">Manage users, roles, and permissions for your organization</p>
            </div>
        </div>

        @if (session()->has('status'))
            <div class="sf-alert sf-alert-success mb-6">
                <i class='bx bx-check-circle'></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Invite User Section -->
        <div class="sf-card mb-6">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-envelope mr-2' style="color: #4361EE;"></i>
                    Invite New User
                </h2>
            </div>
            <div class="p-5">
                <form wire:submit.prevent="sendInvitation" class="space-y-4">
                    <div class="flex items-start gap-4 flex-col md:flex-row">
                        <div class="w-full md:flex-1">
                            <label class="sf-label">Email Address</label>
                            <input type="email" wire:model="email" class="sf-input" placeholder="Enter email address"
                                required>
                            @error('email') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="sf-btn sf-btn-blue mt-6 md:mt-0">
                            <i class='bx bx-send'></i> Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List Section -->
        <div class="sf-card">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-list-ul mr-2' style="color: #4361EE;"></i>
                    All Users
                </h2>
                <span class="sf-badge sf-badge-gray">{{ $users->count() }} users</span>
            </div>
            <div class="overflow-x-auto">
                <table class="sf-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="sf-table-row">
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="sf-avatar">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4361EE&color=fff"
                                                alt="{{ $user->name }}">
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $currentRole = $user->getRoleNames()->first();
                                    @endphp
                                    @if($currentRole)
                                        <span class="sf-role-badge {{ $currentRole === 'admin' ? 'admin' : 'user' }}">
                                            <i class='bx {{ $currentRole === 'admin' ? 'bx-shield' : 'bx-user' }} mr-1'></i>
                                            {{ ucfirst($currentRole) }}
                                        </span>
                                    @else
                                        <span class="sf-role-badge none">
                                            <i class='bx bx-question-mark'></i> No Role
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="sf-actions-group">
                                        <select wire:model="selectedRoles.{{ $user->id }}" class="sf-select">
                                            <option value="">Select Role</option>
                                            @foreach($availableRoles as $role)
                                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="assignRole({{ $user->id }})" class="sf-btn-sm sf-btn-blue"
                                            title="Assign Role">
                                            <i class='bx bx-check'></i>
                                            <span class="hidden sm:inline">Assign</span>
                                        </button>
                                        <button wire:click="removeAllRoles({{ $user->id }})"
                                            class="sf-btn-sm sf-btn-warning" title="Remove All Roles">
                                            <i class='bx bx-trash'></i>
                                            <span class="hidden sm:inline">Remove</span>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})" class="sf-btn-sm sf-btn-red"
                                            title="Delete User">
                                            <i class='bx bx-user-x'></i>
                                            <span class="hidden sm:inline">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        @if ($showDeleteModal)
            <div class="sf-overlay" wire:click.self="cancelDelete">
                <div class="sf-modal sf-modal-sm">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-trash' style="color: #F04438;"></i>
                            Confirm Delete
                        </span>
                        <button type="button" wire:click="cancelDelete" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                    <div class="sf-modal-body">
                        <div class="text-center">
                            <div class="sf-modal-icon sf-modal-icon-danger">
                                <i class='bx bx-question-mark'></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete User</h3>
                            <p class="text-sm text-gray-500">Are you sure you want to delete this user? This action cannot
                                be undone.</p>
                        </div>
                    </div>
                    <div class="sf-modal-foot">
                        <button wire:click="cancelDelete" class="sf-btn sf-btn-ghost">Cancel</button>
                        <button wire:click="deleteUser" class="sf-btn sf-btn-red">
                            <i class='bx bx-check'></i> Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>