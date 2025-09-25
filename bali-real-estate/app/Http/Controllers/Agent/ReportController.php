<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $periodStats = Inquiry::select(DB::raw('DATE(created_at) as date, count(*) as count'))
            ->groupBy('date')->orderBy('date')->get();

        $reservations = Reservation::select('status',DB::raw('count(*) as cnt'))->groupBy('status')->get();

        // Note: Need to add expected_rent column to properties table for this to work
        // $yieldStats = DB::table('properties')->select(DB::raw('ROUND((expected_rent*12/price*100),1) as yield'),DB::raw('count(*) as cnt'))->groupBy('yield')->get();
        $yieldStats = collect(); // Empty for now

        return inertia('Agent/Reports/Index',[
            'periodStats'=>$periodStats,
            'reservations'=>$reservations,
            'yieldStats'=>$yieldStats
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date','Inquiries']);
            $data = Inquiry::select(DB::raw('DATE(created_at) as date, count(*) as count'))->groupBy('date')->get();
            foreach($data as $row){
                fputcsv($handle, [$row->date,$row->count]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback,'report.csv');
    }
}