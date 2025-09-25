@extends('layouts.public')

@section('content')
<h1 class="text-3xl font-bold mb-6">Bali Properties</h1>

<!-- Search Form -->
<form method="GET" action="{{ route('properties.index') }}" class="bg-gray-100 p-6 rounded-lg mb-8">
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
        <div>
            <label for="area" class="block text-sm font-medium text-gray-700">Area</label>
            <select name="area" id="area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">All Areas</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price (USD)</label>
            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
            <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price (USD)</label>
            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
            <input type="number" name="bedrooms" id="bedrooms" value="{{ request('bedrooms') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
            <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
            <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
        
        <div>
            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
            <select name="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                <option value="IDR" {{ $currency == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                <option value="JPY" {{ $currency == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
            </select>
        </div>
    </div>
    
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Search
    </button>
</form>

<!-- Results Count -->
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600">{{ $count }} {{ $count == 1 ? 'result' : 'results' }} found</p>
</div>

<!-- Properties Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($properties as $property)
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <img src="{{ $property->main_image_url }}" alt="{{ $property->title }}" class="w-full h-48 object-cover mb-3">
            <h3 class="text-xl font-semibold mb-2">
                <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:underline">
                    {{ $property->title }}
                </a>
            </h3>
            <div class="mb-2">
                <p class="text-2xl font-bold text-green-600">{{ \App\Support\Price::format($property->price_usd, $currency) }}</p>
                @if($currency !== 'USD')
                    <p class="text-sm text-gray-500">${{ number_format($property->price_usd) }} USD</p>
                @endif
            </div>
            <p class="text-gray-600 mb-2">{{ $property->area->name }}</p>
            <p class="text-gray-500">{{ $property->bedrooms }} bed • {{ $property->bathrooms }} bath</p>
        </div>
    @endforeach
</div>

<!-- Pagination -->
{{ $properties->links() }}
@endsection