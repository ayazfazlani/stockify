<div>
    <div x-data="{
        scanner: null,
        scannerId: '{{ $scannerId }}',
        scannedData: @entangle('scannedData'),
        isScanning: false,
        error: '',
        
        startScan() {
            this.error = '';
            this.scanner = new Html5Qrcode(this.scannerId);
            this.scanner.start(
                { facingMode: 'environment' },
                { 
                    fps: 20, 
                    qrbox: { width: 350, height: 220 },
                    aspectRatio: 1.0 
                },
                (decodedText) => {
                    this.$wire.processScannedCode(decodedText);
                    // this.stopScan(); // Optional: stop after success
                }
            ).then(() => {
                this.isScanning = true;
            }).catch(err => {
                console.error('Camera start error:', err);
                this.error = 'Camera error: ' + err + '. Ensure you are using HTTPS.';
                this.scanner = null;
            });
        },
        
        stopScan() {
            if (this.scanner) {
                this.scanner.stop().then(() => {
                    this.scanner = null;
                    this.isScanning = false;
                }).catch(err => {
                    console.error('Camera stop error:', err);
                });
            }
        }
    }" class="qr-scanner-container">
        <div :id="scannerId" wire:ignore
            style="width:100%; max-width:600px; margin:20px auto; background: #eee; border-radius: 8px; min-height: 200px;">
        </div>

        <div class="text-center mb-4">
            <button type="button" x-show="!isScanning" @click="startScan()"
                class="px-4 py-2 bg-green-500 text-white rounded-md">
                📸 Start Camera Scan
            </button>

            <button type="button" x-show="isScanning" @click="stopScan()"
                class="px-4 py-2 bg-red-500 text-white rounded-md" x-cloak>
                Stop
            </button>

            <div x-show="error" x-text="error" class="mt-2 text-red-500 text-sm" x-cloak></div>
        </div>

        @if($scannedData)
            <p class="text-center">Scanned: <strong>{{ $scannedData }}</strong></p>
        @endif
    </div>

    @once
        @push('scripts')
            <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        @endpush
    @endonce

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>