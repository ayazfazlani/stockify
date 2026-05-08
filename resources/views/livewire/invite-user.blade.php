<div data-stockify>
    <div class="sf-invite-container">
        <div class="sf-invite-card">
            <div class="sf-invite-header">
                <div class="sf-invite-icon">
                    <i class='bx bx-envelope'></i>
                </div>
                <h2 class="sf-invite-title">Invite User</h2>
                <p class="sf-invite-subtitle">Send an invitation to join your organization</p>
            </div>

            <form wire:submit.prevent="sendInvitation" class="sf-invite-form">
                <div class="sf-field">
                    <label class="sf-label">Email Address</label>
                    <div class="sf-input-group">
                        <i class='bx bx-envelope sf-input-icon'></i>
                        <input type="email" wire:model="email" placeholder="user@example.com"
                            class="sf-input sf-input-with-icon" required>
                    </div>
                    @error('email')
                        <div class="sf-ferr">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="sf-btn sf-btn-green sf-btn-full">
                    <i class='bx bx-send'></i> Send Invitation
                </button>

                @if (session()->has('message'))
                    <div class="sf-alert sf-alert-success mt-4">
                        <i class='bx bx-check-circle'></i>
                        {{ session('message') }}
                    </div>
                @elseif (session()->has('error'))
                    <div class="sf-alert sf-alert-error mt-4">
                        <i class='bx bx-error-circle'></i>
                        {{ session('error') }}
                    </div>
                @endif
            </form>

            <div class="sf-invite-footer">
                <p class="sf-invite-note">
                    <i class='bx bx-info-circle'></i>
                    The user will receive an email with instructions to join your organization.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* POSforShops - Invite User Page Styles */
    [data-stockify] .sf-invite-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: #F4F5F8;
        padding: 1.5rem;
    }

    [data-stockify] .sf-invite-card {
        background: white;
        border: 1px solid #E8EAF0;
        border-radius: 4px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(15, 17, 23, 0.08), 0 2px 4px rgba(15, 17, 23, 0.04);
        overflow: hidden;
    }

    [data-stockify] .sf-invite-header {
        text-align: center;
        padding: 2rem 1.5rem 1rem;
        border-bottom: 1px solid #E8EAF0;
        background: #F9FAFB;
    }

    [data-stockify] .sf-invite-icon {
        width: 64px;
        height: 64px;
        background: #EEF1FD;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    [data-stockify] .sf-invite-icon i {
        font-size: 2rem;
        color: #4361EE;
    }

    [data-stockify] .sf-invite-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0F1117;
        margin: 0 0 0.25rem;
    }

    [data-stockify] .sf-invite-subtitle {
        font-size: 0.8125rem;
        color: #9CA3B8;
        margin: 0;
    }

    [data-stockify] .sf-invite-form {
        padding: 1.5rem;
    }

    [data-stockify] .sf-field {
        margin-bottom: 1.5rem;
    }

    [data-stockify] .sf-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #4B5168;
        margin-bottom: 0.375rem;
    }

    [data-stockify] .sf-input-group {
        position: relative;
    }

    [data-stockify] .sf-input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3B8;
        font-size: 1.125rem;
        pointer-events: none;
    }

    [data-stockify] .sf-input {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1.5px solid #E8EAF0;
        border-radius: 2px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    [data-stockify] .sf-input:focus {
        outline: none;
        border-color: #4361EE;
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
    }

    [data-stockify] .sf-input-with-icon {
        padding-left: 2.5rem;
    }

    [data-stockify] .sf-ferr {
        font-size: 0.6875rem;
        color: #F04438;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    [data-stockify] .sf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        border-radius: 2px;
        font-size: 0.8125rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    [data-stockify] .sf-btn-green {
        background: #12B76A;
        color: white;
    }

    [data-stockify] .sf-btn-green:hover {
        background: #0E9F5E;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(18, 183, 106, 0.2);
    }

    [data-stockify] .sf-btn-green:active {
        transform: translateY(0);
    }

    [data-stockify] .sf-alert {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    [data-stockify] .sf-alert-success {
        background: #ECFDF5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }

    [data-stockify] .sf-alert-error {
        background: #FEF3F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    [data-stockify] .sf-invite-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid #E8EAF0;
        background: #F9FAFB;
    }

    [data-stockify] .sf-invite-note {
        font-size: 0.6875rem;
        color: #9CA3B8;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        justify-content: center;
    }

    [data-stockify] .sf-invite-note i {
        font-size: 0.875rem;
    }

    /* Loading state */
    [data-stockify] .sf-btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 480px) {
        [data-stockify] .sf-invite-container {
            padding: 1rem;
        }

        [data-stockify] .sf-invite-header {
            padding: 1.5rem 1rem 1rem;
        }

        [data-stockify] .sf-invite-form {
            padding: 1.25rem;
        }

        [data-stockify] .sf-invite-icon {
            width: 48px;
            height: 48px;
        }

        [data-stockify] .sf-invite-icon i {
            font-size: 1.5rem;
        }

        [data-stockify] .sf-invite-title {
            font-size: 1.25rem;
        }
    }

    /* Dark Mode Support */
    body.dark [data-stockify] .sf-invite-container {
        background: #1a1a2e;
    }

    body.dark [data-stockify] .sf-invite-card {
        background: #1E1E2E;
        border-color: #2D2D3D;
    }

    body.dark [data-stockify] .sf-invite-header {
        background: #2A2A3A;
        border-bottom-color: #2D2D3D;
    }

    body.dark [data-stockify] .sf-invite-title {
        color: #FFFFFF;
    }

    body.dark [data-stockify] .sf-invite-subtitle {
        color: #6B6B8D;
    }

    body.dark [data-stockify] .sf-input {
        background: #2A2A3A;
        border-color: #2D2D3D;
        color: #FFFFFF;
    }

    body.dark [data-stockify] .sf-input:focus {
        border-color: #4361EE;
    }

    body.dark [data-stockify] .sf-label {
        color: #A1A1B9;
    }

    body.dark [data-stockify] .sf-invite-footer {
        background: #2A2A3A;
        border-top-color: #2D2D3D;
    }

    body.dark [data-stockify] .sf-invite-note {
        color: #6B6B8D;
    }

    body.dark [data-stockify] .sf-invite-icon {
        background: #363648;
    }

    body.dark [data-stockify] .sf-alert-success {
        background: rgba(18, 183, 106, 0.1);
        border-color: rgba(18, 183, 106, 0.2);
        color: #34D399;
    }

    body.dark [data-stockify] .sf-alert-error {
        background: rgba(240, 68, 56, 0.1);
        border-color: rgba(240, 68, 56, 0.2);
        color: #F87171;
    }
</style>

<!-- Optional: Add Alpine.js for loading state (optional) -->
<script>
    document.addEventListener('livewire:navigated', function () {
        const form = document.querySelector('.sf-invite-form');
        const submitBtn = document.querySelector('.sf-btn-green');

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.classList.add('sf-btn-loading');
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Sending...';
                submitBtn.disabled = true;
            });
        }
    });
</script>