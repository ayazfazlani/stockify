<div data-stockify-scanner>
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
    }" class="sf-scanner-wrapper">

        <!-- Scanner Preview Area -->
        <div class="sf-scanner-preview">
            <div :id="scannerId" wire:ignore class="sf-scanner-viewport"></div>

            <!-- Placeholder when not scanning -->
            <div x-show="!isScanning" class="sf-scanner-placeholder">
                <i class='bx bx-qr-scan'></i>
                <p>Camera is off</p>
                <p class="sf-scanner-hint">Click the button below to start scanning</p>
            </div>
        </div>

        <!-- Scanner Controls -->
        <div class="sf-scanner-controls">
            <button type="button" x-show="!isScanning" @click="startScan()" class="sf-btn sf-btn-blue">
                <i class='bx bx-camera'></i> Start Camera Scan
            </button>

            <button type="button" x-show="isScanning" @click="stopScan()" class="sf-btn sf-btn-red" x-cloak>
                <i class='bx bx-stop-circle'></i> Stop Scanning
            </button>

            <div x-show="error" x-text="error" class="sf-ferr" x-cloak></div>
        </div>

        <!-- Scanned Result -->
        @if($scannedData)
            <div class="sf-scanner-result">
                <i class='bx bx-check-circle'></i>
                <div>
                    <div class="sf-result-label">Scanned Code:</div>
                    <div class="sf-result-value">{{ $scannedData }}</div>
                </div>
            </div>
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

        /* Scanner Styles */
        [data-stockify-scanner] .sf-scanner-wrapper {
            background: #FFFFFF;
            border: 1px solid #E8EAF0;
            border-radius: 4px;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(15, 17, 23, .04);
        }

        [data-stockify-scanner] .sf-scanner-preview {
            position: relative;
            background: #F9FAFB;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        [data-stockify-scanner] .sf-scanner-viewport {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #1a1a2e;
            border-radius: 4px;
            min-height: 280px;
        }

        [data-stockify-scanner] .sf-scanner-viewport video {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        [data-stockify-scanner] .sf-scanner-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #F9FAFB;
            border: 1px dashed #D1D5E0;
            border-radius: 4px;
            margin: 1rem;
            min-height: 250px;
        }

        [data-stockify-scanner] .sf-scanner-placeholder i {
            font-size: 3rem;
            color: #9CA3B8;
            margin-bottom: 0.75rem;
        }

        [data-stockify-scanner] .sf-scanner-placeholder p {
            font-size: 0.875rem;
            color: #4B5168;
            margin: 0;
        }

        [data-stockify-scanner] .sf-scanner-hint {
            font-size: 0.75rem;
            color: #9CA3B8;
            margin-top: 0.25rem !important;
        }

        [data-stockify-scanner] .sf-scanner-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        [data-stockify-scanner] .sf-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 2px;
            font-size: 0.8125rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        [data-stockify-scanner] .sf-btn-blue {
            background: #4361EE;
            color: white;
        }

        [data-stockify-scanner] .sf-btn-blue:hover {
            background: #364FC7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        [data-stockify-scanner] .sf-btn-red {
            background: #F04438;
            color: white;
        }

        [data-stockify-scanner] .sf-btn-red:hover {
            background: #D73A2E;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(240, 68, 56, 0.2);
        }

        [data-stockify-scanner] .sf-btn:active {
            transform: translateY(0);
        }

        [data-stockify-scanner] .sf-ferr {
            font-size: 0.75rem;
            color: #F04438;
            margin-top: 0.5rem;
            text-align: center;
        }

        [data-stockify-scanner] .sf-scanner-result {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-radius: 4px;
            margin-top: 0.5rem;
        }

        [data-stockify-scanner] .sf-scanner-result i {
            font-size: 1.5rem;
            color: #12B76A;
        }

        [data-stockify-scanner] .sf-result-label {
            font-size: 0.6875rem;
            font-weight: 600;
            color: #065F46;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        [data-stockify-scanner] .sf-result-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #065F46;
            font-family: 'JetBrains Mono', monospace;
            word-break: break-all;
        }

        /* Loading/Scanning animation */
        [data-stockify-scanner] .sf-scanner-viewport.scanning {
            position: relative;
        }

        [data-stockify-scanner] .sf-scanner-viewport.scanning::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #4361EE, transparent);
            animation: scanline 2s linear infinite;
        }

        @keyframes scanline {
            0% {
                top: 0;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0;
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            [data-stockify-scanner] .sf-scanner-wrapper {
                padding: 0.75rem;
            }

            [data-stockify-scanner] .sf-scanner-placeholder {
                min-height: 200px;
                margin: 0.75rem;
            }

            [data-stockify-scanner] .sf-scanner-placeholder i {
                font-size: 2rem;
            }

            [data-stockify-scanner] .sf-scanner-placeholder p {
                font-size: 0.75rem;
            }

            [data-stockify-scanner] .sf-btn {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }
        }

        /* Dark Mode Support */
        body.dark [data-stockify-scanner] .sf-scanner-wrapper {
            background: #1E1E2E;
            border-color: #2D2D3D;
        }

        body.dark [data-stockify-scanner] .sf-scanner-placeholder {
            background: #2A2A3A;
            border-color: #3D3D4D;
        }

        body.dark [data-stockify-scanner] .sf-scanner-placeholder p {
            color: #A1A1B9;
        }

        body.dark [data-stockify-scanner] .sf-scanner-placeholder i {
            color: #6B6B8D;
        }

        body.dark [data-stockify-scanner] .sf-scanner-result {
            background: rgba(18, 183, 106, 0.1);
            border-color: rgba(18, 183, 106, 0.2);
        }

        body.dark [data-stockify-scanner] .sf-result-label,
        body.dark [data-stockify-scanner] .sf-result-value {
            color: #34D399;
        }
    </style>

    <script>
        // Add scanning animation class when scanning starts
        document.addEventListener('livewire:navigated', function () {
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.attributeName === 'class') {
                        const scannerWrapper = document.querySelector('[data-stockify-scanner]');
                        const isScanning = document.querySelector('.sf-scanner-viewport')?.parentElement?.__x?.$data?.isScanning;
                        const viewport = document.querySelector('.sf-scanner-viewport');
                        if (viewport) {
                            if (isScanning) {
                                viewport.classList.add('scanning');
                            } else {
                                viewport.classList.remove('scanning');
                            }
                        }
                    }
                });
            });

            const target = document.querySelector('[data-stockify-scanner]');
            if (target) {
                observer.observe(target, { attributes: true, subtree: true });
            }
        });
    </script>
</div>