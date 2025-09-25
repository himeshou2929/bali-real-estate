<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['area','images'])->where('is_published', true);

        // Basic filters
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('area', function($areaQuery) use ($keyword) {
                      $areaQuery->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('beds') && $request->beds > 0) {
            $query->where('bedrooms', '>=', $request->beds);
        }

        if ($request->filled('baths') && $request->baths > 0) {
            $query->where('bathrooms', '>=', $request->baths);
        }

        if ($request->filled('yield_min') && $request->yield_min > 0) {
            $query->where('yield_percentage', '>=', $request->yield_min);
        }

        // Rental tenure filters
        if ($request->filled('has_monthly_rent') && $request->has_monthly_rent == '1') {
            $query->whereNotNull('monthly_rent_price');
            if ($request->filled('monthly_rent_max') && $request->monthly_rent_max > 0) {
                $query->where('monthly_rent_price', '<=', $request->monthly_rent_max);
            }
        }

        if ($request->filled('has_yearly_rent') && $request->has_yearly_rent == '1') {
            $query->whereNotNull('yearly_rent_price');
            if ($request->filled('yearly_rent_max') && $request->yearly_rent_max > 0) {
                $query->where('yearly_rent_price', '<=', $request->yearly_rent_max);
            }
        }

        if ($request->filled('has_leasehold') && $request->has_leasehold == '1') {
            $query->whereNotNull('leasehold_price');
            if ($request->filled('leasehold_price_max') && $request->leasehold_price_max > 0) {
                $query->where('leasehold_price', '<=', $request->leasehold_price_max);
            }
        }

        if ($request->filled('has_freehold') && $request->has_freehold == '1') {
            $query->whereNotNull('freehold_price');
            if ($request->filled('freehold_price_max') && $request->freehold_price_max > 0) {
                $query->where('freehold_price', '<=', $request->freehold_price_max);
            }
        }

        // Apply sorting
        $sort = $request->get('sort', '');
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price_usd', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_usd', 'desc');
                break;
            case '':
            default:
                $query->latest();
                break;
        }

        $properties = $query->paginate(12)->withQueryString();
        $areas = Area::all();

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'areas' => $areas,
            'filters' => [
                'areas' => $areas,
                'bedrooms' => [1,2,3,4,5],
            ],
            'query' => (object) array_merge($request->all(), ['sort' => $sort]),
        ]);
    }

    public function show(Property $property)
    {
        $property->load(['area','images' => fn($q) => $q->orderBy('sort_order')]);
        return Inertia::render('Properties/Show', ['property' => $property]);
    }
}