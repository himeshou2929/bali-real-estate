@component('mail::message')
# New Property Inquiry

**Type:** {{ $inquiry->type }}  
**Subject:** {{ $inquiry->subject }}

**Property:** {{ $inquiry->property->title }} (Area: {{ $inquiry->property->area->name ?? '-' }})  
**Price:** {{ number_format($inquiry->property->price_usd) }} USD

**From:** {{ $inquiry->name }} ({{ $inquiry->email }})  
**Phone:** {{ $inquiry->phone ?? '-' }}  
**Preferred Contact:** {{ $inquiry->contact_method }}  
**Preferred Date:** {{ optional($inquiry->preferred_date)->format('Y-m-d H:i') ?? '-' }}

**Message:**
{{ $inquiry->message }}

@component('mail::button', ['url' => url('/dashboard/inquiries/'.$inquiry->id)])
Open in Dashboard
@endcomponent
@endcomponent