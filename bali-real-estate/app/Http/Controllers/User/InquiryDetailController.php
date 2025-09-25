<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryDetailController extends Controller
{
    public function show(Request $request, Inquiry $inquiry)
    {
        // Check if user owns this inquiry
        if ($inquiry->user_id !== $request->user()->id) {
            abort(403);
        }

        $inquiry->load(['property','assignedUser']);

        return inertia('User/InquiryDetail',[
            'inquiry'=>$inquiry
        ]);
    }
}