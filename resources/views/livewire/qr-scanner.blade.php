<div>
    <h2>Scan QR / Barcode for Stock</h2>

    <div id="reader" style="width:100%; max-width:520px; margin:20px auto; border:2px solid #ddd;"></div>

    <div class="text-center my-4">
        <button wire:ignore id="start-btn" class="btn btn-success me-2">📷 Start Camera</button>
        <button wire:ignore id="stop-btn" class="btn btn-danger me-2" style="display:none;">Stop Camera</button>
        <label class="btn btn-primary">
            📤 Upload Product Image / Label
            <input type="file" id="file-input" accept="image/*" capture="environment" style="display:none;">
        </label>
    </div>

    <p class="mt-3">
        Scanned Code: <strong id="result">{{ $scannedData ?? 'Nothing scanned yet...' }}</strong>
    </p>

    <button wire:click="clearScan" class="btn btn-secondary">Clear</button>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;

        function startCamera() {
            if (html5QrCode) return;

            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 15,
                qrbox: { width: 300, height: 180 },   // wider = better for barcodes
                aspectRatio: 1.0,
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.QR_CODE,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.ITF,
                    Html5QrcodeSupportedFormats.DATA_MATRIX
                ]
            };

            html5QrCode.start(
                { facingMode: "environment" },   // back camera
                config,
                (decodedText) => {               // SUCCESS
                    @this.set('scannedData', decodedText);
                    document.getElementById('result').textContent = decodedText;
                    // Optional: stop after success
                    // stopCamera();
                },
                (error) => { /* ignore normal "no code found" */ }
            );

            document.getElementById('start-btn').style.display = 'none';
            document.getElementById('stop-btn').style.display = 'inline-block';
        }

        function stopCamera() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode = null;
                    document.getElementById('start-btn').style.display = 'inline-block';
                    document.getElementById('stop-btn').style.display = 'none';
                });
            }
        }

        // Upload image handler
        document.getElementById('file-input').addEventListener('change', function (e) {
            if (!e.target.files.length) return;
            const imageFile = e.target.files[0];

            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");

            html5QrCode.scanFile(imageFile, true)
                .then((decodedText) => {
                    @this.set('scannedData', decodedText);
                    document.getElementById('result').textContent = decodedText;
                })
                .catch(err => {
                    alert("Could not read this image.\nTry better lighting or a clearer photo.");
                });
        });

        // Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            document.getElementById('start-btn').addEventListener('click', startCamera);
            document.getElementById('stop-btn').addEventListener('click', stopCamera);
        }); <div>
            <h2>Fast Stock Scan (QR + Barcode)</h2>

            <div id="reader" style="width:100%; max-width:600px; margin:20px auto;"></div>

            <div class="text-center">
                <button wire:ignore id="start-btn" class="btn btn-success">📸 Start Fast Camera Scan</button>
                <button wire:ignore id="stop-btn" class="btn btn-danger" style="display:none;">Stop</button>
                <label class="btn btn-primary ms-2">
                    📤 Upload Label Image
                    <input type="file" id="upload" accept="image/*" style="display:none;">
                </label>
            </div>

            <p>Scanned: <strong id="result">{{ $scannedData ?? '—' }}</strong></p>
        </div>

        @push('scripts')
                <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
            <script>
                let scanner = null;

                function startFastScan() {
                    if (scanner) return;
                scanner = new Html5Qrcode("reader");

                scanner.start(
                {facingMode: "environment" },
                {
                    fps: 25,                          // MAX speed
                qrbox: {width: 350, height: 220 }, // wide = better for barcodes
                aspectRatio: 1.0,
                formatsToSupport: [
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.EAN_13, Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.CODE_128, Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.UPC_A, Html5QrcodeSupportedFormats.ITF
                ]
                        },
                        (decodedText) => {
                    @this.set('scannedData', decodedText);   // send to Livewire
                document.getElementById('result').textContent = decodedText;
                            // Auto-process stock here if you want
                            // @this.call('processScan', decodedText);
                        },
                        () => { } // ignore "no code" errors
                );

                document.getElementById('start-btn').style.display = 'none';
                document.getElementById('stop-btn').style.display = 'inline-block';
                }

                function stopScan() {
                    if (scanner) scanner.stop().then(() => {scanner = null; });
                document.getElementById('start-btn').style.display = 'inline-block';
                document.getElementById('stop-btn').style.display = 'none';
                }

                // Upload (very reliable for difficult labels)
                document.getElementById('upload').addEventListener('change', e => {
                    const file = e.target.files[0];
                if (!file) return;
                const tempScanner = new Html5Qrcode("reader");
                tempScanner.scanFile(file, true)
                        .then(text => {
                    @this.set('scannedData', text);
                document.getElementById('result').textContent = text;
                        })
                        .catch(() => alert("Could not read this image. Try clearer photo."));
                });

                document.addEventListener('livewire:navigated', () => {
                    document.getElementById('start-btn').addEventListener('click', startFastScan);
                document.getElementById('stop-btn').addEventListener('click', stopScan);
                });
            </script>
        @endpush
    </script>
@endpush