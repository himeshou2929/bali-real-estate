<x-mail::message>
# {{ $inquiry->subject }}

{!! nl2br(e($bodyText)) !!}

@isset($signature)
---

{!! nl2br(e($signature)) !!}
@endisset

@isset($property)
<x-mail::panel>
**Property**: {{ $property->title ?? 'Property #' . $property->id }}

@isset($property->price_usd)
Price: ${{ number_format($property->price_usd) }}
@endisset
@isset($property->address)
Address: {{ $property->address }}
@endisset
</x-mail::panel>
@endisset

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
