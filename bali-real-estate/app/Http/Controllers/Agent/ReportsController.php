<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // 物件統計
        $propertyStats = [
            'total' => Property::where('agent_id', $user->id)->count(),
            'published' => Property::where('agent_id', $user->id)->where('is_published', true)->count(),
            'available' => Property::where('agent_id', $user->id)->where('status', 'available')->count(),
            'pending' => Property::where('agent_id', $user->id)->where('status', 'pending')->count(),
        ];
        
        // 問い合わせ統計
        $inquiryStats = [
            'total' => Inquiry::whereHas('property', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->count(),
            'this_month' => Inquiry::whereHas('property', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->whereMonth('created_at', date('m'))->count(),
            'this_week' => Inquiry::whereHas('property', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
        
        // エリア別物件数
        $propertiesByArea = Property::select('areas.name', DB::raw('count(*) as count'))
            ->join('areas', 'properties.area_id', '=', 'areas.id')
            ->where('agent_id', $user->id)
            ->groupBy('areas.name')
            ->orderBy('count', 'desc')
            ->get();
        
        // 価格帯別物件数
        $propertiesByPriceRange = Property::where('agent_id', $user->id)
            ->selectRaw('
                CASE
                    WHEN price_usd < 200000 THEN "200,000 USD未満"
                    WHEN price_usd < 400000 THEN "200,000-400,000 USD"
                    WHEN price_usd < 600000 THEN "400,000-600,000 USD"
                    WHEN price_usd < 800000 THEN "600,000-800,000 USD"
                    ELSE "800,000 USD以上"
                END as price_range,
                count(*) as count
            ')
            ->groupBy('price_range')
            ->orderBy('count', 'desc')
            ->get();
        
        // 月別問い合わせ数（過去6ヶ月）
        $monthlyInquiries = Inquiry::whereHas('property', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'month' => $item->year . '年' . $item->month . '月',
                    'count' => $item->count
                ];
            });

        return inertia('Agent/Reports', [
            'propertyStats' => $propertyStats,
            'inquiryStats' => $inquiryStats,
            'propertiesByArea' => $propertiesByArea,
            'propertiesByPriceRange' => $propertiesByPriceRange,
            'monthlyInquiries' => $monthlyInquiries,
        ]);
    }
}
