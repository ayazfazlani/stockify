<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Stockify' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; margin: 0; padding: 0; background: #f1f5f9; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .email-header { background: #0f172a; padding: 32px 40px; text-align: center; }
        .email-header img { height: 32px; }
        .email-header h1 { color: #ffffff; font-size: 20px; font-weight: 700; margin: 12px 0 0; }
        .email-body { padding: 40px; }
        .email-footer { background: #f8fafc; padding: 24px 40px; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 13px; margin: 0; }
        .btn { display: inline-block; padding: 12px 28px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .btn-success { background: #16a34a; }
        .btn-danger { background: #dc2626; }
        .btn-outline { background: transparent; border: 2px solid #2563eb; color: #2563eb; }
        .card { background: #f8fafc; border-radius: 12px; padding: 20px; margin: 16px 0; }
        .stat-grid { display: table; width: 100%; }
        .stat-cell { display: table-cell; width: 33.33%; text-align: center; padding: 16px; }
        .stat-value { font-size: 24px; font-weight: 700; color: #0f172a; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
        .item-row { display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .item-row:last-child { border-bottom: none; }
        .item-img { width: 48px; height: 48px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin-right: 16px; }
        .item-img img { max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 8px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-4 { margin-top: 24px; }
        .mb-4 { margin-bottom: 24px; }
        .text-gray { color: #64748b; }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 12px; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .divider { height: 1px; background: #e2e8f0; margin: 24px 0; }
        @media only screen and (max-width: 600px) {
            .email-body { padding: 24px !important; }
            .stat-cell { display: block; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div style="display: inline-flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; background: #2563eb; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                    <span style="color: #fff; font-weight: 700; font-size: 16px;">S</span>
                </div>
                <h1>Stockify</h1>
            </div>
        </div>

        <div class="email-body">
            {{ $slot }}
        </div>

        <div class="email-footer">
            <p>{{ __('mail.footer_powered') }} <strong>Stockify</strong></p>
            <p style="margin-top: 8px; font-size: 12px;">{{ __('mail.footer_help') }} <a href="mailto:support@stockify.app" style="color: #2563eb;">support@stockify.app</a></p>
            <p style="margin-top: 16px; font-size: 11px; color: #cbd5e1;">
                {{ __('mail.footer_unsubscribe') }} <a href="{{ url('/unsubscribe') }}" style="color: #94a3b8;">{{ __('mail.here') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
