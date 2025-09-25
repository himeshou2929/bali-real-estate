<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Inertia\Inertia;

class PropertyShowController extends Controller
{
    public function show(Property $property)
    {
        $property->load(['area']);
        return Inertia::render('Properties/Show', ['property' => $property]);
    }
}