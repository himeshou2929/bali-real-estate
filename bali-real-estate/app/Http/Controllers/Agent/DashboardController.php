<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Agent-specific statistics
        $totalProperties = Property::where('agent_id', $user->id)->count();
        
        $totalInquiries = Inquiry::whereHas('property', function($q) use ($user) {
            $q->where('agent_id', $user->id);
        })->count();
        
        // Recent inquiries for this agent's properties
        $recentInquiries = Inquiry::with(['property', 'user'])
            ->whereHas('property', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Properties for this agent
        $agentProperties = Property::where('agent_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return inertia('Agent/Dashboard', [
            'stats' => [
                'total_properties' => $totalProperties,
                'total_inquiries' => $totalInquiries,
            ],
            'recent_inquiries' => $recentInquiries,
            'agent_properties' => $agentProperties,
        ]);
    }
}