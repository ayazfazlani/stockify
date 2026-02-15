@component('mail::message')
# Welcome to {{ config('app.name') }}!

Hi {{ $user->name }},

We're excited to have you and your team join us! Here's everything you need to get started with {{ config('app.name')
}}.

## Quick Start Guide
1. Complete your team profile
2. Invite your team members
3. Set up your first inventory items
4. Configure your subscription plan

@component('mail::button', ['url' => $setupUrl])
Start Setup
@endcomponent

## Helpful Resources
- [Documentation]({{ $docsUrl }})
- [Video Tutorials]({{ url('/tutorials') }})
- [Support Center]({{ url('/support') }})

@component('mail::button', ['url' => $dashboardUrl])
Go to Dashboard
@endcomponent

If you need any help getting started, don't hesitate to reach out to our support team.

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent