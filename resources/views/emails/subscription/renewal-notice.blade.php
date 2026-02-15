@component('mail::message')
# {{ $isTrialEnding ? 'Trial Period Ending Soon' : 'Subscription Renewal Notice' }}

Dear {{ $team->owner->name }},

@if($isTrialEnding)
Your trial period for {{ config('app.name') }} will end in 3 days. To continue using our services without interruption,
please set up your payment method and choose a subscription plan.

@component('mail::button', ['url' => route('subscription.show')])
Choose a Plan
@endcomponent

@else
Your subscription for {{ config('app.name') }} will renew on {{ $team->subscription('default')->ends_at->format('F j,
Y') }}.

The renewal amount will be ${{ number_format($team->subscription('default')->upcomingInvoice()->total() / 100, 2) }} for
the {{ $team->subscription_plan }} plan.

To view or update your subscription details, please click the button below:

@component('mail::button', ['url' => route('subscription.manage')])
Manage Subscription
@endcomponent

@endif

Thank you for using {{ config('app.name') }}!

Best regards,<br>
{{ config('app.name') }}
@endcomponent