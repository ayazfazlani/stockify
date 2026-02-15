<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('message') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Card -->
    <div class="card bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="card-header px-6 py-4 border-b flex items-center justify-between">
            <h2 class="card-title text-lg font-semibold">Tenant Management</h2>
            <button wire:click="create" class="btn btn-primary btn-sm inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Add New Tenant
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="billing-table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stripe Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $tenant->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tenant->slug }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{-- {{ $tenant->owner?->name ?? '—' }} --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $tenant->plan_name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                            {{-- {{ Str::limit($tenant->stripe_price_id ?? '—', 18, '...') }} --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $tenant->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $tenant->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $tenant->active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $tenant->id }})"
                                    class="btn btn-outline btn-sm text-blue-600 hover:text-blue-800"
                                    title="Edit tenant">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $tenant->id }})"
                                    class="btn btn-outline btn-sm text-red-600 hover:text-red-800"
                                    wire:confirm="Are you sure you want to delete tenant '{{ $tenant->name }}'?"
                                    title="Delete tenant">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No tenants found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if you add it later) -->
        <!-- <div class="px-6 py-4 border-t">
            {{-- {{ $tenants->links() }} --}}
        </div> -->
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold">
                    {{ $editingId ? 'Edit Tenant' : 'Create New Tenant' }}
                </h3>
                <button wire:click="cancel" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Left column -->
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Tenant Name *</label>
                            <input type="text" class="form-input" wire:model.debounce.500ms="name"
                                placeholder="Acme Inc">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Slug *</label>
                            <input type="text" class="form-input" wire:model.debounce.500ms="slug" placeholder="acme">
                            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Owner Email / Name</label>
                            <input type="text" class="form-input" wire:model="owner" placeholder="john@example.com">
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Plan Name</label>
                            <input type="text" class="form-input" wire:model="plan_name" placeholder="Pro Monthly">
                        </div>

                        <div>
                            <label class="form-label">Stripe Price ID</label>
                            <input type="text" class="form-input font-mono" wire:model="stripe_price_id"
                                placeholder="price_1N...">
                        </div>

                        <div class="pt-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="rounded border-gray-300" wire:model="active">
                                <span>Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button type="button" wire:click="cancel" class="btn btn-outline">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            {{ $editingId ? 'Update Tenant' : 'Create Tenant' }}
                        </span>
                        <span wire:loading>
                            {{ $editingId ? 'Updating...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>