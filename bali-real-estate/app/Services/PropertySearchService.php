<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertySearchService
{
    public function build(Request $req): Builder
    {
        $q          = trim((string)$req->query('q', ''));
        $areaId     = (int) $req->query('area_id', 0);
        $minPrice   = (int) $req->query('min_price', 0);
        $maxPrice   = (int) $req->query('max_price', 0);
        $beds       = (int) $req->query('beds', 0);
        $baths      = (int) $req->query('baths', 0);
        $yieldMin   = (float) $req->query('yield_min', 0);
        $sort       = (string) $req->query('sort', 'latest');
        
        // 新しい価格フィルター（チェックボックス制御）
        $hasMonthlyRent = $req->query('has_monthly_rent') === '1';
        $monthlyRentMax = (int) $req->query('monthly_rent_max', 0);
        $hasYearlyRent = $req->query('has_yearly_rent') === '1';
        $yearlyRentMax  = (int) $req->query('yearly_rent_max', 0);
        $hasLeasehold = $req->query('has_leasehold') === '1';
        $leaseholdPriceMax = (int) $req->query('leasehold_price_max', 0);
        $hasFreehold = $req->query('has_freehold') === '1';
        $freeholdPriceMax  = (int) $req->query('freehold_price_max', 0);

        $query = Property::query()->with(['area','images'])->where('is_published', true);

        if ($q !== '') {
            $query->where(function($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%");
            });
        }

        if ($areaId) { $query->where('area_id', $areaId); }

        if ($beds)     { $query->where('bedrooms', '>=', $beds); }
        if ($baths)    { $query->where('bathrooms', '>=', $baths); }
        if ($minPrice) { $query->where('price_usd', '>=', $minPrice); }
        if ($maxPrice) { $query->where('price_usd', '<=', $maxPrice); }

        // レンタル & 所有形態価格フィルター（チェックボックスがONの場合のみ）
        if ($hasMonthlyRent) { 
            // 月額レンタルがNULLでない（金額が入力されている）物件のみ
            $query->whereNotNull('monthly_rent')
                  ->where('monthly_rent', '>', 0);
            // 上限金額が指定されている場合は、その金額以下に絞り込み
            if ($monthlyRentMax > 0) {
                $query->where('monthly_rent', '<=', $monthlyRentMax);
            }
        }
        
        if ($hasYearlyRent) { 
            // 年間レンタルがNULLでない（金額が入力されている）物件のみ
            $query->whereNotNull('yearly_rent')
                  ->where('yearly_rent', '>', 0);
            // 上限金額が指定されている場合は、その金額以下に絞り込み
            if ($yearlyRentMax > 0) {
                $query->where('yearly_rent', '<=', $yearlyRentMax);
            }
        }
        
        if ($hasLeasehold) { 
            // LEASEHOLD価格がNULLでない（金額が入力されている）物件のみ
            $query->whereNotNull('leasehold_price')
                  ->where('leasehold_price', '>', 0);
            // 上限金額が指定されている場合は、その金額以下に絞り込み
            if ($leaseholdPriceMax > 0) {
                $query->where('leasehold_price', '<=', $leaseholdPriceMax);
            }
        }
        
        if ($hasFreehold) { 
            // FREEHOLD価格がNULLでない（金額が入力されている）物件のみ
            $query->whereNotNull('freehold_price')
                  ->where('freehold_price', '>', 0);
            // 上限金額が指定されている場合は、その金額以下に絞り込み
            if ($freeholdPriceMax > 0) {
                $query->where('freehold_price', '<=', $freeholdPriceMax);
            }
        }

        // 利回り(投資用): yield_percent >=
        if ($yieldMin) { $query->where('yield_percent','>=',$yieldMin); }

        // ソート
        switch ($sort) {
            case 'price_asc':   $query->orderBy('price_usd','asc'); break;
            case 'price_desc':  $query->orderBy('price_usd','desc'); break;
            case 'land_desc':   $query->orderBy('land_sqm','desc'); break;
            case 'build_desc':  $query->orderBy('building_sqm','desc'); break;
            case 'popular':     $query->orderBy('views_count','desc')->orderBy('created_at','desc'); break;
            case 'latest':
            default:            $query->orderBy('created_at','desc');
        }

        return $query;
    }
}