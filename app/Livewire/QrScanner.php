<?php

namespace App\Livewire;

use Livewire\Component;

class QrScanner extends Component
{
    public $scannedData = null;

    public $scannedCode = '';

    public function updatedScannedCode($value)
    {
        if (empty($value))
            return;

        // Find product by SKU or barcode
        $product = Product::where('sku', $value)->orWhere('barcode', $value)->first();

        if ($product) {
            $product->increment('stock_quantity', 1); // or whatever action you want
            session()->flash('success', "Stock +1 for {$product->name}");
            $this->scannedCode = ''; // reset for next scan
        } else {
            session()->flash('error', 'Product not found');
        }
    }

    // Optional: Barcode too
    public function generateBarcode()
    {
        // Use picqer/php-barcode-generator
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodePath = "barcodes/{$this->sku}.png";
        file_put_contents(
            storage_path("app/public/{$barcodePath}"),
            $generator->getBarcode($this->sku, $generator::TYPE_CODE_128)
        );
        $this->barcode = $barcodePath;
        $this->save();
    }
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
