<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class OnboardingWizard extends Component
{
    public $step = 1;
    public $totalSteps = 4;
    
    // Step 1: Team Profile
    public $teamName;
    public $industry;
    public $size;
    
    // Step 2: Subscription
    public $plan;
    
    // Step 3: Initial Settings
    public $defaultCurrency;
    public $timezone;
    
    public function mount()
    {
        $team = Auth::user()->currentTeam;
        $this->teamName = $team->name;
    }
    
    public function nextStep()
    {
        $this->validateStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }
    
    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }
    
    public function validateStep()
    {
        switch ($this->step) {
            case 1:
                $this->validate([
                    'teamName' => 'required|min:3',
                    'industry' => 'required',
                    'size' => 'required',
                ]);
                $this->updateTeamProfile();
                break;
                
            case 2:
                $this->validate([
                    'plan' => 'required',
                ]);
                break;
                
            case 3:
                $this->validate([
                    'defaultCurrency' => 'required',
                    'timezone' => 'required',
                ]);
                $this->updateTeamSettings();
                break;
        }
    }
    
    private function updateTeamProfile()
    {
        $team = Auth::user()->currentTeam;
        $team->update([
            'name' => $this->teamName,
            'industry' => $this->industry,
            'size' => $this->size,
        ]);
    }
    
    private function updateTeamSettings()
    {
        $team = Auth::user()->currentTeam;
        $team->update([
            'default_currency' => $this->defaultCurrency,
            'timezone' => $this->timezone,
        ]);
    }
    
    public function completeOnboarding()
    {
        $this->validateStep();
        
        $team = Auth::user()->currentTeam;
        $team->update(['onboarding_completed' => true]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Welcome aboard! Your setup is complete.');
    }
    
    public function render()
    {
        return view('livewire.onboarding-wizard');
    }
}