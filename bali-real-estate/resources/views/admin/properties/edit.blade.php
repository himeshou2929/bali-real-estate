@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Property</h1>
        <a href="{{ route('admin.properties.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Back to Properties
        </a>
    </div>

    <form method="POST" action="{{ route('admin.properties.update', $property) }}" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('title', $property->title) }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="area_id" class="block text-sm font-medium text-gray-700">Area</label>
                <select name="area_id" id="area_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select Area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_id', $property->area_id) == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
                @error('area_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="price_usd" class="block text-sm font-medium text-gray-700">Price (USD)</label>
                <input type="number" name="price_usd" id="price_usd" required min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('price_usd', $property->price_usd) }}">
                @error('price_usd')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                <input type="number" name="bedrooms" id="bedrooms" required min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('bedrooms', $property->bedrooms) }}">
                @error('bedrooms')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                <input type="number" name="bathrooms" id="bathrooms" required min="1"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('bathrooms', $property->bathrooms) }}">
                @error('bathrooms')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="land_sqm" class="block text-sm font-medium text-gray-700">Land Size (sqm)</label>
                <input type="number" name="land_sqm" id="land_sqm" min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('land_sqm', $property->land_sqm) }}">
                @error('land_sqm')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="building_sqm" class="block text-sm font-medium text-gray-700">Building Size (sqm)</label>
                <input type="number" name="building_sqm" id="building_sqm" min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                       value="{{ old('building_sqm', $property->building_sqm) }}">
                @error('building_sqm')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $property->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Current Images -->
        @if($property->images->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($property->images->sortBy('sort_order') as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-24 object-cover rounded-md border">
                            <span class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">{{ $image->sort_order }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">Note: Image deletion will be available in a future update.</p>
            </div>
        @endif

        <!-- Add New Images -->
        <div class="mb-6">
            <label for="images" class="block text-sm font-medium text-gray-700">Add New Images</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <p class="text-sm text-gray-500 mt-1">Select multiple images. JPG, JPEG, PNG only. Max 4MB each.</p>
            @error('images.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            
            <!-- Preview container -->
            <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $property->is_published) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                <span class="ml-2 text-sm text-gray-700">Published</span>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Update Property
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    if (e.target.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(e.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-md border">
                        <span class="absolute top-1 right-1 bg-green-500 text-white text-xs px-1 rounded">NEW</span>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection