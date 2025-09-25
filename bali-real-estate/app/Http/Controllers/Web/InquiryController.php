<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryStoreRequest;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquirySubmitted;

class InquiryController extends Controller
{
    public function store(InquiryStoreRequest $request)
    {
        $validated = $request->validated();
        
        $inquiry = Inquiry::create($validated);
        
        // 管理者へ通知メール（キュー送信）
        Mail::to(config('mail.admin_address'))->queue(new InquirySubmitted($inquiry));
        
        return redirect()->back()->with('status', 'Your inquiry has been sent successfully!');
    }
}