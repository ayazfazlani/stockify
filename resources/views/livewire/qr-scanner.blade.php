<div>
    <h2>Scan QR Code for Stock</h2>

    <!-- Scanner container -->
    <div id="reader" style="width: 100%; max-width: 600px; margin: 0 auto;"></div>

    <!-- Result area -->
    <div wire:ignore>
        <p>Scanned Code: <strong id="scanned-result">{{ $scannedData ?? 'Nothing yet...' }}</strong></p>
    </div>

    <button wire:click="clearScan">Clear</button>
</div>

@push('scripts')
    <script>
        let html5QrCodeScanner;

        function onScanSuccess(decodedText, decodedResult) {
            // Send the scanned data to Livewire
            @this.set('scannedData', decodedText);

            // Optional: Stop scanning after success
            // html5QrCodeScanner.clear();

            // You can also trigger an action immediately
            // @this.call('processScannedCode', decodedText);
        }

        // Initialize scanner when component loads
        document.addEventListener('livewire:navigated', () => {
            if (document.getElementById('reader')) {
                html5QrCodeScanner = new Html5QrcodeScanner(
                    "reader",
                    {
                        fps: 10,              // frames per second
                        qrbox: { width: 250, height: 250 }, // scanning box size
                        aspectRatio: 1.0,
                        facingMode: "environment" // use back camera on mobile
                    },
                    false // verbose = false
                );

                html5QrCodeScanner.render(onScanSuccess);
            }
        });

        // Clean up when component is removed (good practice)
        document.addEventListener('livewire:navigated', () => {
            if (html5QrCodeScanner) {
                html5QrCodeScanner.clear();
            }
        });
    </script>
@endpush