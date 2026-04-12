<?php

namespace App\Livewire;

use Livewire\Component;

class QrScanner extends Component
{
    public $scannedData = null;

    public function processScannedCode($code)
    {
        // $code contains the QR data (e.g., product SKU, ID, etc.)

        // Example: Find product and update stock
        // $product = Product::where('sku', $code)->first();
        // if ($product) {
        //     $product->increment('stock', 1);
        //     session()->flash('message', 'Stock updated for ' . $product->name);
        // }

        $this->scannedData = $code;
    }

    public function clearScan()
    {

        dd($this->scannedData);
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
