@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manage Properties</h1>
        <a href="{{ route('admin.properties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Add New Property
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($properties as $property)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($property->images->first())
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ asset('storage/' . $property->images->first()->path) }}" 
                                         alt="{{ $property->title }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300"></div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                <div class="text-sm text-gray-500">{{ $property->area->name }} • ${{ number_format($property->price_usd) }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $property->is_published ? 'Published' : 'Draft' }}
                            </span>
                            <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            <a href="{{ route('admin.properties.edit', $property) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this property?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 text-center text-gray-500">
                    No properties found. <a href="{{ route('admin.properties.create') }}" class="text-blue-600 hover:underline">Create the first one!</a>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6">
        {{ $properties->links() }}
    </div>
</div>
@endsection