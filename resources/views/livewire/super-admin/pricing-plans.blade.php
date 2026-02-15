<div>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Plans</div>
                <div class="stat-card-icon" style="background-color: #10b981;">
                    <i class="fas fa-list"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $plans->total() }}</div>
            <div class="stat-card-desc">All pricing plans</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Plans</div>
                <div class="stat-card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ \App\Models\Plan::where('active', true)->count() }}</div>
            <div class="stat-card-desc">Currently active</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Plans</div>
                <div class="stat-card-icon" style="background-color: #f59e0b;">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ \App\Models\Plan::where('interval', 'month')->count() }}</div>
            <div class="stat-card-desc">Billed monthly</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Yearly Plans</div>
                <div class="stat-card-icon" style="background-color: #8b5cf6;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ \App\Models\Plan::where('interval', 'year')->count() }}</div>
            <div class="stat-card-desc">Billed yearly</div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('message') }}
    </div>
    @endif

    <!-- Plans Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Pricing Plans Management</div>
            <div class="card-actions">
                <button class="btn btn-primary btn-sm" wire:click="create">
                    <i class="fas fa-plus"></i> Add New Plan
                </button>
            </div>
        </div>

        <table class="billing-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Price</th>
                    <th>Interval</th>
                    <th>Stripe ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                <tr>
                    <td class="font-medium">{{ $plan->name }}</td>
                    <td class="text-sm text-gray-500">{{ $plan->slug }}</td>
                    <td>${{ number_format($plan->amount / 100, 2) }} {{ strtoupper($plan->currency) }}</td>
                    <td>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $plan->interval === 'year' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($plan->interval) }}ly
                        </span>
                    </td>
                    <td class="text-sm text-gray-500 font-mono">{{ Str::limit($plan->stripe_price_id, 20) }}</td>
                    <td>
                        <button wire:click="toggleActive({{ $plan->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $plan->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $plan->active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $plan->id }})" class="btn btn-outline btn-sm" title="Edit Plan">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $plan->id }})" class="btn btn-outline btn-sm text-red-600"
                                onclick="return confirm('Are you sure you want to delete this plan?')"
                                title="Delete Plan">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $plans->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">
                    {{ $editingId ? 'Edit Plan' : 'Create New Plan' }}
                </h3>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Basic Information</h4>

                        <div class="form-group">
                            <label class="form-label">Plan Name *</label>
                            <input type="text" class="form-input" wire:model="name" placeholder="e.g., Pro Plan">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug *</label>
                            <input type="text" class="form-input" wire:model="slug" placeholder="e.g., pro-monthly">
                            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label class="form-label">Stripe Price ID *</label>
                            <input type="text" class="form-input" wire:model="stripe_price_id"
                                placeholder="e.g., price_1ABC123...">
                            @error('stripe_price_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div> --}}
                    </div>

                    <!-- Pricing Information -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Pricing Information</h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Amount ($) *</label>
                                <input type="number" step="0.01" class="form-input" wire:model="amount"
                                    placeholder="29.99">
                                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Currency *</label>
                                <select class="form-select" wire:model="currency">
                                    <option value="usd">USD ($)</option>
                                    <option value="eur">EUR (€)</option>
                                    <option value="gbp">GBP (£)</option>
                                    <option value="cad">CAD ($)</option>
                                </select>
                                @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Billing Interval *</label>
                            <select class="form-select" wire:model="interval">
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                            @error('interval') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 mr-2" wire:model="active">
                                Active Plan
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Features Rich Text Editor -->
                <div class="form-group">
                    <label class="form-label">Features</label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <!-- Toolbar -->
                        <div class="border-b border-gray-300 bg-gray-50 p-2 flex flex-wrap gap-1">
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="formatText('bold')"
                                title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="formatText('italic')"
                                title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded"
                                onclick="formatText('underline')" title="Underline">
                                <i class="fas fa-underline"></i>
                            </button>
                            <div class="w-px h-6 bg-gray-300 mx-1"></div>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="insertList('ul')"
                                title="Bullet List">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="insertList('ol')"
                                title="Numbered List">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div class="w-px h-6 bg-gray-300 mx-1"></div>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="insertCheck()"
                                title="Check Item">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="p-2 hover:bg-gray-200 rounded" onclick="insertCross()"
                                title="Cross Item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Editor Area -->
                        <textarea id="featuresEditor" class="form-input border-0 min-h-[200px] resize-none"
                            wire:model="features"
                            placeholder="Enter plan features... You can use HTML formatting or plain text."></textarea>
                    </div>
                    <div class="form-hint mt-2">
                        You can use HTML tags or plain text. Common features format: &lt;ul&gt;&lt;li&gt;Feature
                        1&lt;/li&gt;&lt;li&gt;Feature 2&lt;/li&gt;&lt;/ul&gt;
                    </div>
                    @error('features') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Preview Section -->
                @if($features)
                <div class="form-group">
                    <label class="form-label">Features Preview</label>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 min-h-[100px]">
                        {!! $features !!}
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" class="btn btn-outline" wire:click="$set('showModal', false)">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove>
                            {{ $editingId ? 'Update Plan' : 'Create Plan' }}
                        </span>
                        <span wire:loading> {{ $editingId ? 'Updating..' : 'Createing..' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <style>
        /* Additional styles for the rich text editor */
        #featuresEditor {
            min-height: 200px;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        #featuresEditor:focus {
            outline: none;
            box-shadow: none;
        }

        /* Style the preview area */
        .preview-area ul,
        .preview-area ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .preview-area li {
            margin-bottom: 0.5rem;
        }
    </style>
</div>

<!-- Rich Text Editor Script -->
<script>
    function formatText(command) {
    const editor = document.getElementById('featuresEditor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const selectedText = editor.value.substring(start, end);
    
    let formattedText = '';
    switch(command) {
        case 'bold':
            formattedText = `<strong>${selectedText}</strong>`;
            break;
        case 'italic':
            formattedText = `<em>${selectedText}</em>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
    }
    
    editor.value = editor.value.substring(0, start) + formattedText + editor.value.substring(end);
    editor.focus();
    editor.setSelectionRange(start + formattedText.length, start + formattedText.length);
    
    // Update Livewire model
    editor.dispatchEvent(new Event('input'));
}

function insertList(type) {
    const editor = document.getElementById('featuresEditor');
    const start = editor.selectionStart;
    
    let listHTML = type === 'ul' 
        ? '<ul>\n<li>List item</li>\n<li>List item</li>\n</ul>'
        : '<ol>\n<li>List item</li>\n<li>List item</li>\n</ol>';
    
    editor.value = editor.value.substring(0, start) + listHTML + editor.value.substring(start);
    editor.focus();
    editor.setSelectionRange(start, start);
    
    // Update Livewire model
    editor.dispatchEvent(new Event('input'));
}

function insertCheck() {
    const editor = document.getElementById('featuresEditor');
    const start = editor.selectionStart;
    
    const checkItem = '<li><i class="fas fa-check text-green-500 mr-2"></i>Feature item</li>\n';
    
    editor.value = editor.value.substring(0, start) + checkItem + editor.value.substring(start);
    editor.focus();
    editor.setSelectionRange(start, start);
    
    // Update Livewire model
    editor.dispatchEvent(new Event('input'));
}

function insertCross() {
    const editor = document.getElementById('featuresEditor');
    const start = editor.selectionStart;
    
    const crossItem = '<li><i class="fas fa-times text-red-500 mr-2"></i>Limited feature</li>\n';
    
    editor.value = editor.value.substring(0, start) + crossItem + editor.value.substring(start);
    editor.focus();
    editor.setSelectionRange(start, start);
    
    // Update Livewire model
    editor.dispatchEvent(new Event('input'));
}

// Initialize when modal opens
document.addEventListener('livewire:init', () => {
    Livewire.on('showModalChanged', (value) => {
        if (value) {
            // Focus on editor when modal opens
            setTimeout(() => {
                const editor = document.getElementById('featuresEditor');
                if (editor) editor.focus();
            }, 100);
        }
    });
});
</script>