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
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('team admin'))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Create New Role</h3>
                    <div class="flex space-x-4">
                        <input type="text" wire:model="newRoleName" class="flex-1 rounded-md border-gray-300 shadow-sm"
                            placeholder="Enter role name">
                        <button wire:click="createRole"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>
                    </div>
                </div>
                @endif

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

                <!-- Available Permissions -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Available Permissions</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <span class="text-sm">{{ ucfirst($permission) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>