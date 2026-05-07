<div data-stockify>
    <div class="sf-container max-w-7xl mx-auto px-4 py-8">
        <div class="sf-header mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-shield-quarter mr-3 text-blue-600'></i>
                    Role & Permission Management
                </h1>
                <p class="text-gray-500 mt-1 text-sm md:text-base">Define custom roles and assign granular permissions
                    for your team members.</p>
            </div>
            <button wire:click="openCreateModal"
                class="sf-btn sf-btn-blue flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition-all duration-200">
                <i class='bx bx-plus-circle mr-2 text-lg'></i>
                Create Custom Role
            </button>
        </div>

        @if (session()->has('status'))
            <div
                class="sf-alert sf-alert-success mb-6 p-4 bg-green-50 border-l-4 border-green-500 flex items-center text-green-700">
                <i class='bx bx-check-circle mr-3 text-xl'></i>
                {{ session('status') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div
                class="sf-alert sf-alert-danger mb-6 p-4 bg-red-50 border-l-4 border-red-500 flex items-center text-red-700">
                <i class='bx bx-error-circle mr-3 text-xl'></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="sf-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Role Name
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Type</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Permissions</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Users
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ ucfirst($role->name) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($role->tenant_id === null)
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">System
                                            Role</span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Custom
                                            Role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-md">
                                        @php $count = $role->permissions->count(); @endphp
                                        @foreach($role->permissions->take(3) as $perm)
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] whitespace-nowrap">{{ $perm->name }}</span>
                                        @endforeach
                                        @if($count > 3)
                                            <span
                                                class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[10px]">+{{ $count - 3 }}
                                                more</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $role->users()->count() ?: 0 }} users
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if ($role->tenant_id !== null)
                                        <button wire:click="editRole({{ $role->id }})"
                                            class="sf-icon-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100">
                                            <i class='bx bx-edit text-xl'></i>
                                        </button>
                                        <button
                                            onclick="confirm('Are you sure you want to delete this custom role?') || event.stopImmediatePropagation()"
                                            wire:click="deleteRole({{ $role->id }})"
                                            class="sf-icon-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100">
                                            <i class='bx bx-trash text-xl'></i>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Read-only System Role</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compact Modal - Smaller & Scrollable -->
        @if ($showRoleModal)
            <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data="{ show: true }" x-show="show"
                x-cloak>
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showRoleModal', false)"></div>

                <div
                    class="relative bg-white w-full max-w-3xl rounded-xl shadow-xl overflow-hidden transition-all transform animate-in fade-in zoom-in duration-200">
                    <!-- Modal Header - Compact -->
                    <div class="px-6 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i
                                class='bx {{ $isEditing ? "bx-edit-alt" : "bx-plus-circle" }} mr-2 text-blue-600 text-xl'></i>
                            {{ $isEditing ? 'Edit Custom Role' : 'Create Custom Role' }}
                        </h3>
                        <button wire:click="$set('showRoleModal', false)"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-200">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <!-- Modal Body - Scrollable (Fixed Height) -->
                    <div class="overflow-y-auto" style="max-height: 500px;">
                        <div class="p-6">
                            <!-- Role Name Section - Compact -->
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">
                                    Role Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="roleName"
                                    class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 outline-none text-gray-900 text-sm"
                                    placeholder="e.g. Inventory Manager">
                                @error('roleName')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Permissions Section - Compact -->
                            <div>
                                <div class="flex items-center justify-between mb-3 pb-1 border-b border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Assign Permissions
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="selectAllPermissions"
                                            class="text-xs text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                                            Select All
                                        </button>
                                        <span class="text-gray-300 text-xs">|</span>
                                        <button type="button" wire:click="deselectAllPermissions"
                                            class="text-xs text-gray-500 hover:text-gray-700 font-semibold transition-colors">
                                            Deselect All
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($allPermissions as $category => $permissions)
                                        <div class="bg-gray-50/30 rounded-lg p-3 border border-gray-100">
                                            <div
                                                class="flex items-center space-x-1 text-blue-700 mb-2 pb-1 border-b border-blue-100">
                                                <i class='bx bx-folder-open text-base'></i>
                                                <h5 class="text-xs font-bold uppercase tracking-wide">{{ $category }}</h5>
                                            </div>
                                            <div class="space-y-2 max-h-36 overflow-y-auto custom-scrollbar-small pr-1">
                                                @foreach ($permissions as $name => $description)
                                                    <label
                                                        class="flex items-start group cursor-pointer hover:bg-white/50 rounded px-1 py-0.5 transition-all">
                                                        <div class="relative flex items-center mt-0.5">
                                                            <input type="checkbox" value="{{ $name }}"
                                                                wire:model="selectedPermissions"
                                                                class="peer h-3.5 w-3.5 cursor-pointer appearance-none rounded border border-gray-300 transition-all checked:bg-blue-600 checked:border-blue-600 focus:ring-1 focus:ring-blue-500">
                                                            <i
                                                                class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 left-0.5 top-0.5 text-[8px]'></i>
                                                        </div>
                                                        <div class="ml-2 flex-1">
                                                            <div
                                                                class="text-xs font-semibold text-gray-800 group-hover:text-blue-700 transition-colors">
                                                                {{ $name }}</div>
                                                            <div class="text-[10px] text-gray-500 italic leading-tight">
                                                                {{ $description }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedPermissions')
                                    <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer - Compact -->
                    <div class="px-6 py-3 border-t border-gray-100 flex justify-end items-center space-x-2 bg-gray-50">
                        <button wire:click="$set('showRoleModal', false)"
                            class="px-4 py-1.5 text-xs font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="saveRole" wire:loading.attr="disabled"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-1.5 rounded-lg font-bold shadow-sm active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1 text-sm">
                            <span wire:loading.remove wire:target="saveRole">
                                {{ $isEditing ? 'Update' : 'Save' }}
                            </span>
                            <span wire:loading wire:target="saveRole">
                                <i class='bx bx-loader-alt animate-spin text-white text-sm'></i> Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-in {
        animation: zoomIn 0.2s ease-out;
    }

    [x-cloak] {
        display: none !important;
    }

    /* Custom Scrollbar */
    .custom-scrollbar-small::-webkit-scrollbar {
        width: 3px;
    }

    .custom-scrollbar-small::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .custom-scrollbar-small::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .custom-scrollbar-small::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Better checkbox styling */
    input[type="checkbox"] {
        transition: all 0.2s ease;
    }

    input[type="checkbox"]:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    input[type="checkbox"]:focus {
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
        outline: none;
    }

    /* Custom scrollbar for modal body */
    .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 5px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && @json($showRoleModal)) {
                @this.set('showRoleModal', false);
            }
        });
    });
</script>