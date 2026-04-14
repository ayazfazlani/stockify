<?php

namespace App\Livewire;

use Livewire\Component;

class QrScanner extends Component
{
    public $scannedData = null;
    public $scannerId = 'reader'; // Default ID

    public function mount($scannerId = 'reader')
    {
        $this->scannerId = $scannerId;
    }

    public function processScannedCode($code)
    {
        if (empty($code)) {
            return;
        }

        $this->scannedData = $code;
        
        // Dispatch event with the scanner ID so listeners know which one fired
        $this->dispatch('scannedData', code: $code, scannerId: $this->scannerId);
    }

    public function clearScan()
    {
        $this->scannedData = null;
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
