<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('area')->latest()->paginate(15);
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $areas = Area::all();
        return view('admin.properties.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'title' => 'required|max:255',
            'price_usd' => 'required|integer|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:1',
            'land_sqm' => 'nullable|integer|min:0',
            'building_sqm' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $property = Property::create($validated);

        if ($request->hasFile('images')) {
            $this->handleImageUploads($request->file('images'), $property);
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        $property->load('area', 'images');
        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $areas = Area::all();
        $property->load('images');
        return view('admin.properties.edit', compact('property', 'areas'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'title' => 'required|max:255',
            'price_usd' => 'required|integer|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:1',
            'land_sqm' => 'nullable|integer|min:0',
            'building_sqm' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $property->update($validated);

        if ($request->hasFile('images')) {
            $this->handleImageUploads($request->file('images'), $property);
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        // Delete all associated images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        $property->delete();
        
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    private function handleImageUploads($images, Property $property)
    {
        $nextSortOrder = $property->images()->max('sort_order') + 1 ?? 1;

        foreach ($images as $index => $image) {
            $timestamp = now()->timestamp;
            $filename = "{$timestamp}_{$index}.{$image->getClientOriginalExtension()}";
            $path = "properties/{$property->id}/{$filename}";
            
            Storage::disk('public')->putFileAs(
                "properties/{$property->id}",
                $image,
                $filename
            );

            Image::create([
                'property_id' => $property->id,
                'path' => $path,
                'sort_order' => $nextSortOrder + $index,
            ]);
        }
    }
}