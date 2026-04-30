<?php

namespace App\Livewire;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeamSwitcher extends Component
{
    public $currentTeamId;

    public $teams = [];

    protected $listeners = ['refreshTeams' => 'loadTeams'];

    public function mount()
    {
        $this->loadTeams();
    }

    // public function loadTeams()
    // {
    //   $this->teams = Auth::user()->accessibleTeams();
    //   $this->currentTeamId = session('current_team_id', Auth::user()->current_team_id);
    // }

    // public function switchTeam()
    // {
    //   if (!$this->teams->contains('id', $this->currentTeamId)) {
    //     session()->flash('error', 'Invalid team selection');
    //     return;
    //   }

    //   try {
    //     // Update session and user's current team
    //     session(['current_team_id' => $this->currentTeamId]);
    //     Auth::user()->update(['current_team_id' => $this->currentTeamId]);

    //     // Flash success message
    //     session()->flash('status', 'Team switched successfully!');

    //     // Emit event for other components
    //     $this->dispatch('teamChanged', $this->currentTeamId);

    //     // Refresh the page
    //     return redirect(request()->header('Referer'));
    //   } catch (\Exception $e) {
    //     session()->flash('error', 'Error switching teams');
    //   }
    // }
    public function loadTeams()
    {
        $currentTenantId = tenant('id');
        $this->teams = Auth::user()->accessibleTeams()->where('tenant_id', $currentTenantId)->values();
        $this->currentTeamId = Auth::user()->getCurrentStoreId();
    }

    public function switchTeam()
    {
        // Validate team belongs to user's accessible teams
        if (! collect($this->teams)->contains('id', $this->currentTeamId)) {
            abort(403, 'Unauthorized team selection');
        }

        $store = collect($this->teams)->where('id', $this->currentTeamId)->first();
        $tenant = Tenant::find($store->tenant_id);

        // Update session and database
        session([
            'current_store_id' => $this->currentTeamId,
        ]);
        Auth::user()->update([
            'store_id' => $this->currentTeamId,
        ]);
        session()->flash('status', 'Team switched successfully!');

        if ($tenant) {
            return redirect()->route('tenant.items', ['tenant' => $tenant->slug]);
        }

        return redirect()->route('home'); // Fallback
    }

    public function render()
    {
        return view('livewire.team-switcher');
    }
}
