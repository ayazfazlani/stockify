@component('emails.layout')
@slot('subject')
{{ $isAdmin ? __('mail.new_expense_logged') : __('mail.expense_confirmation') }}
@endslot

<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">
    {{ $isAdmin ? __('mail.expense_team_title', ['name' => $user->name]) : __('mail.expense_logged_title') }}
</h2>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.amount') }}</p>
            <p style="margin: 4px 0 0; font-weight: 700; color: #dc2626; font-size: 28px;">{{ config('app.currency_symbol') }}{{ number_format($expense->amount, 2) }}</p>
        </div>
        <span class="badge badge-danger">{{ __('mail.expense') }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.category') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $expense->category?->name ?? 'Uncategorized' }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.date') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $expense->date?->toDateString() ?? now()->toDateString() }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.store') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $expense->store?->name ?? 'Main Store' }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.logged_by') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $user->name }}</p>
        </div>
    </div>

    @if($expense->description)
    <div class="divider"></div>
    <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.description') }}</p>
    <p style="margin: 4px 0 0; color: #0f172a;">{{ $expense->description }}</p>
    @endif
</div>

<div class="text-center mt-4">
    <a href="{{ url('/expenses') }}" class="btn">
        {{ __('mail.view_expenses') }}
    </a>
</div>
@endcomponent
