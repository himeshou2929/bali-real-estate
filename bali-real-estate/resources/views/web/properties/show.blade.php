@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Currency Selector -->
    <div class="mb-4">
        <form method="GET" action="{{ route('properties.show', $property) }}" class="flex items-center space-x-2">
            <label for="currency" class="text-sm font-medium text-gray-700">Currency:</label>
            <select name="currency" id="currency" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                <option value="IDR" {{ $currency == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                <option value="JPY" {{ $currency == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
            </select>
        </form>
    </div>

    <!-- Property Header -->
    @php $images = $property->images->sortBy('sort_order'); @endphp
    @if($images->isNotEmpty())
      <div class="mb-6">
        <img src="{{ asset('storage/' . ltrim($images->first()->path,'/')) }}"
             alt="{{ $property->title }}" class="w-full max-w-3xl rounded mb-4">
        <div class="grid grid-cols-4 gap-3 max-w-3xl">
          @foreach($images as $img)
            <img src="{{ asset('storage/' . ltrim($img->path,'/')) }}"
                 alt="{{ $property->title }}" class="h-24 w-full object-cover rounded">
          @endforeach
        </div>
      </div>
    @else
      <img src="{{ $property->main_image_url }}" alt="{{ $property->title }}"
           class="w-full max-w-3xl rounded mb-6">
    @endif
    <h1 class="text-4xl font-bold mb-2">{{ $property->title }}</h1>
    <div class="mb-4">
        <p class="text-3xl font-bold text-green-600">{{ \App\Support\Price::format($property->price_usd, $currency) }}</p>
        @if($currency !== 'USD')
            <p class="text-lg text-gray-500">${{ number_format($property->price_usd) }} USD</p>
        @endif
    </div>
    <p class="text-xl text-gray-600 mb-6">{{ $property->area->name }}</p>

    <!-- Property Details -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
                <span class="text-gray-500">Bedrooms</span>
                <p class="font-semibold">{{ $property->bedrooms }}</p>
            </div>
            <div>
                <span class="text-gray-500">Bathrooms</span>
                <p class="font-semibold">{{ $property->bathrooms }}</p>
            </div>
            @if($property->land_sqm)
            <div>
                <span class="text-gray-500">Land Size</span>
                <p class="font-semibold">{{ $property->land_sqm }} sqm</p>
            </div>
            @endif
            @if($property->building_sqm)
            <div>
                <span class="text-gray-500">Building Size</span>
                <p class="font-semibold">{{ $property->building_sqm }} sqm</p>
            </div>
            @endif
        </div>

        @if($property->description)
        <div>
            <h3 class="text-lg font-semibold mb-2">Description</h3>
            <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
        </div>
        @endif
    </div>

    <!-- Status Message -->
    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('status') }}
        </div>
    @endif

    <!-- Inquiry Form -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h3 class="text-2xl font-semibold mb-4">Send Inquiry</h3>
        
        <form method="POST" action="{{ route('inquiries.store') }}">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                <input type="text" name="phone" id="phone" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('phone') }}">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Message (optional)</label>
                <textarea name="message" id="message" rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Send Inquiry
            </button>
        </form>
    </div>
</div>
@endsection