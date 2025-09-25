<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\StorePropertyRequest;
use App\Http\Requests\Agent\UpdatePropertyRequest;
use App\Models\Area;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class PropertyController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('viewAny', Property::class);

        $q = Property::query()
            ->with(['area'])
            ->when(auth()->user()->role === 'agent', fn($qq) => $qq->where('agent_id', auth()->id()))
            ->orderByDesc('created_at');

        $per = (int)($request->integer('per_page') ?: 20);
        $props = $q->paginate($per)->through(function($p){
            return [
                'id'=>$p->id,
                'title'=>$p->title,
                'price_usd'=>$p->price_usd,
                'currency'=>$p->currency ?? 'USD',
                'status'=>$p->status ?? 'available',
                'area'=>['name'=>$p->area->name ?? null],
                'featured_image_url'=>$p->featured_image ? Storage::url($p->featured_image) : null,
            ];
        });

        return Inertia::render('Agent/Properties/Index', [
            'properties'=>$props,
            'agent' => $request->user()->only(['id','name','email','phone','avatar','company','title','bio','website','whatsapp','line_id','location']),
            'filters'=>[
                'per_page'=>$per,
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Property::class);

        $areas = Area::select('id','name')->orderBy('name')->get();
        return Inertia::render('Agent/Properties/Create', ['areas'=>$areas]);
    }

    public function store(StorePropertyRequest $request)
    {
        $this->authorize('create', Property::class);

        $payload = $request->validated();
        
        $p = new Property($payload);
        $p->agent_id = auth()->id();

        // 画像（任意）
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties','public');
            $p->featured_image = $path;
        }

        $p->save();

        // 追加画像の保存
        if ($request->has('additional_images')) {
            foreach ($request->file('additional_images') as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('properties', 'public');
                    \App\Models\PropertyImage::create([
                        'property_id' => $p->id,
                        'url' => $path,
                        'sort_order' => $index + 1,
                        'alt' => $p->title . ' - 画像' . ($index + 1)
                    ]);
                }
            }
        }

        return redirect()->route('agent.properties.index')->with('success','Property created successfully');
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $areas = Area::select('id','name')->orderBy('name')->get();
        return Inertia::render('Agent/Properties/Edit', [
            'property'=>[
                'id'=>$property->id,
                'title'=>$property->title,
                'price_usd'=>$property->price_usd,
                'currency'=>$property->currency ?? 'USD',
                'status'=>$property->status ?? 'available',
                'area_id'=>$property->area_id,
                'bedrooms'=>$property->bedrooms,
                'bathrooms'=>$property->bathrooms,
                'land_sqm'=>$property->land_sqm,
                'building_sqm'=>$property->building_sqm,
                'description'=>$property->description,
                'address'=>$property->address,
                'latitude'=>$property->latitude,
                'longitude'=>$property->longitude,
                'is_published'=>(bool)$property->is_published,
                'featured_image_url'=>$property->featured_image ? Storage::url($property->featured_image) : null,
                'monthly_rent'=>$property->monthly_rent,
                'yearly_rent'=>$property->yearly_rent,
                'leasehold_price'=>$property->leasehold_price,
                'freehold_price'=>$property->freehold_price,
                'yield_percent'=>$property->yield_percent,
                'leasehold_years'=>$property->leasehold_years,
            ],
            'areas'=>$areas
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);

        $payload = $request->validated();
        
        $property->fill($payload);

        if ($request->hasFile('featured_image')) {
            if ($property->featured_image) { Storage::disk('public')->delete($property->featured_image); }
            $property->featured_image = $request->file('featured_image')->store('properties','public');
        }

        $property->save();

        return redirect()->route('agent.properties.index')->with('success','Property updated successfully');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        if ($property->featured_image) { Storage::disk('public')->delete($property->featured_image); }
        $property->delete();
        return back()->with('success','Property deleted successfully');
    }
}