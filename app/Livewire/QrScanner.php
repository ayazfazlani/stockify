<?php

namespace App\Livewire;

use Livewire\Component;

class QrScanner extends Component
{
    public $scannedData = null;

    // app/Models/Product.php
    public function generateQrCode()
    {
        $data = $this->sku;                    // or "https://yourapp.com/product/".$this->id

        // Generate QR Code (install: composer require simplesoftwareio/simple-qrcode)
        $qrPath = "qrcodes/{$this->sku}.png";
        \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)
            ->format('png')
            ->generate($data, storage_path("app/public/{$qrPath}"));

        $this->qr_code = $qrPath;
        $this->save();
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
