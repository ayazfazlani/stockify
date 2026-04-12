<div>
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
                { facingMode: "environment" },
                {
                    fps: 25,                          // MAX speed
                    qrbox: { width: 350, height: 220 }, // wide = better for barcodes
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
            if (scanner) scanner.stop().then(() => { scanner = null; });
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