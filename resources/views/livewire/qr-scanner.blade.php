<div>
    <h2>Scan QR Code for Stock</h2>

    <div id="reader" style="width: 100%; max-width: 500px; margin: 20px auto; border: 2px solid #ccc;"></div>

    <div class="text-center my-3">
        <button wire:ignore id="start-scan-btn" class="btn btn-primary">Start Camera Scan</button>
        <button wire:ignore id="stop-scan-btn" class="btn btn-danger" style="display:none;">Stop Scanning</button>
    </div>

    <p>Scanned Code: <strong id="scanned-result">{{ $scannedData ?? 'Nothing yet...' }}</strong></p>

    <button wire:click="clearScan" class="btn btn-secondary">Clear</button>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let scanner = null;

        function startScanner() {
            if (scanner) return;

            scanner = new Html5QrcodeScanner(
                "reader",
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    facingMode: "environment"
                },
                false
            );

            scanner.render((decodedText) => {
                @this.set('scannedData', decodedText);
                // Optional: auto stop after scan
                // scanner.clear();
                document.getElementById('stop-scan-btn').style.display = 'none';
            });

            document.getElementById('start-scan-btn').style.display = 'none';
            document.getElementById('stop-scan-btn').style.display = 'inline-block';
        }

        function stopScanner() {
            if (scanner) {
                scanner.clear().then(() => {
                    scanner = null;
                    document.getElementById('start-scan-btn').style.display = 'inline-block';
                    document.getElementById('stop-scan-btn').style.display = 'none';
                });
            }
        }

        // Button events
        document.addEventListener('livewire:navigated', () => {
            const startBtn = document.getElementById('start-scan-btn');
            const stopBtn = document.getElementById('stop-scan-btn');

            if (startBtn) startBtn.addEventListener('click', startScanner);
            if (stopBtn) stopBtn.addEventListener('click', stopScanner);
        });
    </script>
@endpush