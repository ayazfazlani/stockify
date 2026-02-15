<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Tenants extends Component
{
    public $tenants;

    // Modal & form state
    public $showModal = false;

    public $editingId = null;

    // Form fields
    public $name = '';

    public $slug = '';

    public $owner = '';

    public $plan_name = '';

    public $stripe_price_id = '';

    public $active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:100|regex:/^[a-z0-9\-]+$/|unique:tenants,slug',
        'owner' => 'nullable|string|max:255',
        'plan_name' => 'nullable|string|max:100',
        'stripe_price_id' => 'nullable|string|max:100',
        'active' => 'boolean',
    ];

    public function mount()
    {
        $this->tenants = Tenant::latest()->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);

        $this->editingId = $tenant->id;
        $this->name = $tenant->name;
        $this->slug = $tenant->slug;
        $this->owner = $tenant->owner?->email ?? $tenant->owner?->name ?? '';
        $this->plan_name = $tenant->plan_name;
        $this->stripe_price_id = $tenant->stripe_price_id;
        $this->active = $tenant->active;

        $this->showModal = true;
    }

    public function save()
    {
        // When editing → ignore own slug in unique rule
        $this->rules['slug'] .= $this->editingId ? "|unique:tenants,slug,{$this->editingId}" : '';

        $validated = $this->validate();

        if ($this->editingId) {
            $tenant = Tenant::findOrFail($this->editingId);
            $tenant->update($validated);
            session()->flash('message', 'Tenant updated successfully.');
        } else {
            Tenant::create($validated);
            session()->flash('message', 'Tenant created successfully.');
        }

        $this->resetForm();
        $this->showModal = false;
        $this->tenants = Tenant::latest()->get(); // refresh
    }

    public function delete($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        session()->flash('message', 'Tenant deleted successfully.');
        $this->tenants = Tenant::latest()->get();
    }

    public function toggleActive($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['active' => ! $tenant->active]);

        session()->flash('message', 'Tenant status updated.');
        $this->tenants = Tenant::with('owner')->latest()->get();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    private function resetForm()
    {
        $this->reset([
            'editingId',
            'name',
            'slug',
            'owner',
            'plan_name',
            'stripe_price_id',
            'active',
        ]);

        $this->resetValidation();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.tenants');
    }
}
