@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $property->title }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.properties.edit', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Edit Property
            </a>
            <a href="{{ route('admin.properties.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Back to Properties
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Property Images -->
        <div class="lg:col-span-2">
            @if($property->images->count() > 0)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Property Images</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($property->images->sortBy('sort_order') as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     class="w-full h-48 object-cover rounded-lg border shadow-sm"
                                     alt="Property image {{ $image->sort_order }}">
                                <span class="absolute top-2 left-2 bg-black bg-opacity-75 text-white text-sm px-2 py-1 rounded">
                                    {{ $image->sort_order }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No images</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload images to showcase this property.</p>
                </div>
            @endif
        </div>

        <!-- Property Details -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Property Details</h2>
            
            <div class="space-y-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Status</span>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $property->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-500">Area</span>
                    <p class="mt-1 text-sm text-gray-900">{{ $property->area->name }}</p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-500">Price</span>
                    <p class="mt-1 text-lg font-semibold text-green-600">${{ number_format($property->price_usd) }} USD</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Bedrooms</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $property->bedrooms }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Bathrooms</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $property->bathrooms }}</p>
                    </div>
                </div>

                @if($property->land_sqm || $property->building_sqm)
                    <div class="grid grid-cols-2 gap-4">
                        @if($property->land_sqm)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Land Size</span>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($property->land_sqm) }} sqm</p>
                            </div>
                        @endif
                        @if($property->building_sqm)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Building Size</span>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($property->building_sqm) }} sqm</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($property->description)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Description</span>
                        <p class="mt-1 text-sm text-gray-900 leading-relaxed">{{ $property->description }}</p>
                    </div>
                @endif

                <div>
                    <span class="text-sm font-medium text-gray-500">Created</span>
                    <p class="mt-1 text-sm text-gray-900">{{ $property->created_at->format('M j, Y g:i A') }}</p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                    <p class="mt-1 text-sm text-gray-900">{{ $property->updated_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex space-x-3">
                <a href="{{ route('properties.show', $property) }}" target="_blank" 
                   class="flex-1 bg-green-600 text-white text-center px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                    View Public Page
                </a>
                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="flex-1"
                      onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                        Delete Property
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection