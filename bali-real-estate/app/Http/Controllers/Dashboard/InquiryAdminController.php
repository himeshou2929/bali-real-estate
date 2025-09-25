<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InquiryAdminController extends Controller
{
    public function show(\App\Models\Inquiry $inquiry) {
        $inquiry->load(["property:id,title,area_id,price_usd,bedrooms,bathrooms,land_sqm,building_sqm","property.area:id,name"]);
        return \Inertia\Inertia::render("Dashboard/Inquiries/Show", [
            "inquiry" => $inquiry,
            "statusOptions" => [
                ["value"=>"new","label"=>"New"],
                ["value"=>"contacted","label"=>"Contacted"],
                ["value"=>"closed","label"=>"Closed"],
            ],
        ]);
    }

    public function index(Request $request)
    {
        $status   = $request->get('status');             // new/contacted/closed
        $keyword  = $request->get('keyword');            // name/email/message/property name
        $fromDate = $request->get('from');               // YYYY-MM-DD
        $toDate   = $request->get('to');                 // YYYY-MM-DD

        $q = Inquiry::query()
            ->with(['property:id,title,area_id', 'property.area:id,name'])
            ->orderByDesc('id');

        if ($status) {
            $q->where('status', $status);
        }

        if ($keyword) {
            $q->where(function($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('message', 'like', "%{$keyword}%")
                    ->orWhereHas('property', function($p) use ($keyword){
                        $p->where('title', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($fromDate) {
            $q->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $q->whereDate('created_at', '<=', $toDate);
        }

        $inquiries = $q->paginate($request->integer('per_page', 20))->withQueryString();

        return Inertia::render('Dashboard/Inquiries/Index', [
            'inquiries' => $inquiries,
            'filters' => [
                'status' => $status,
                'keyword'=> $keyword,
                'from'   => $fromDate,
                'to'     => $toDate,
            ],
            'statusOptions' => [
                ['value' => 'new', 'label' => 'New'],
                ['value' => 'contacted', 'label' => 'Contacted'],
                ['value' => 'closed', 'label' => 'Closed'],
            ],
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => ['required','in:new,contacted,closed']
        ]);
        $inquiry->update(['status' => $request->string('status')]);
        return back()->with('success', 'Status updated.');
    }
}