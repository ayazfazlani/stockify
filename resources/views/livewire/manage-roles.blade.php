<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Manage Roles for {{ $user->name }}</h2>

                @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Assign Existing Role -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Assign Role</h3>
                    <div class="flex space-x-4">
                        <select wire:model="selectedRole" class="flex-1 rounded-md border-gray-300 shadow-sm">
                            <option value="">Select a role</option>
                            @foreach($allRoles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        <button wire:click="addRole"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Assign
                        </button>
                    </div>
                </div>

                <!-- Create New Role -->
                @can('manage roles')
                <div class="mb-8 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Create Custom Role</h3>
                    
                    @feature('custom-roles')
                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <input type="text" wire:model="newRoleName" class="flex-1 rounded-md border-gray-300 shadow-sm"
                                    placeholder="Enter custom role name (e.g. Inventory Auditor)">
                                <button wire:click="createRole"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-sm">
                                    <i class="fas fa-plus"></i> Create Role
                                </button>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Assign Permissions to this Role</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($permissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission }}" 
                                            id="perm_{{ $loop->index }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <label for="perm_{{ $loop->index }}" class="text-xs text-gray-600 cursor-pointer">
                                            {{ ucfirst(str_replace(['view ', 'manage '], ['', ''], $permission)) }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Custom Roles are a <strong>Premium</strong> feature. Please upgrade your plan to create bespoke roles for your team.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endfeature
                </div>
                @endcan

                <!-- Current Roles -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Current Roles</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($user->roles->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->roles as $role)
                            <div class="flex justify-between items-center">
                                <span class="font-medium">{{ ucfirst($role->name) }}</span>
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('team admin'))
                                <button wire:click="removeRole('{{ $role->name }}')"
                                    class="bg-red-500 hover:bg-red-700 text-white text-sm py-1 px-3 rounded">
                                    Remove
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500">No roles assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Available Permissions Legend -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Global Permissions Reference</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($permissions as $permission)
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shield-alt text-gray-300 text-xs"></i>
                                <span class="text-xs text-gray-500">{{ ucfirst($permission) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>