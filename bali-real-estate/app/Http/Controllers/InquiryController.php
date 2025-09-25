<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Mail\InquirySubmitted;
use App\Services\InquiryReplyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $inquiries = Inquiry::with(['property.area', 'user'])
            ->latest()
            ->paginate(20);
            
        return Inertia::render('Dashboard/Inquiries/Index', [
            'inquiries' => $inquiries,
            'filters' => $request->only(['status', 'keyword', 'from', 'to'])
        ]);
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['property', 'user']);
        
        return Inertia::render('Dashboard/Inquiries/Show', [
            'inquiry' => $inquiry
        ]);
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,replied,closed'],
            'agent_reply' => ['nullable', 'string', 'max:5000']
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Inquiry updated successfully.');
    }

    public function create(Request $request)
    {
        $propertyId = (int) $request->query("property_id", 0);
        $property = null;
        if ($propertyId) {
            $property = Property::query()->select("id","title","price_usd","area_id")
                ->with(["area:id,name"]) ->find($propertyId);
        }
        return Inertia::render("Inquiries/Create", [
            "property" => $property,
            "defaults" => [
                "name" => auth()->user()->name ?? "",
                "email" => auth()->user()->email ?? "",
                "phone" => "",
                "type" => "general",
                "contact_method" => "email",
                "preferred_date" => "",
                "subject" => "",
                "message" => "",
                "property_id" => $propertyId,
            ]
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id'    => ['required','exists:properties,id'],
            'name'           => ['required','string','max:255'],
            'email'          => ['required','email','max:255'],
            'phone'          => ['nullable','string','max:255'],
            'message'        => ['required','string','max:5000'],
            // 仕様項目
            'type'           => ['required','in:viewing,info,offer,other'],
            'subject'        => ['required','string','max:500'],
            'contact_method' => ['required','in:email,phone,both'],
            'preferred_date' => ['nullable','date'],
        ]);

        $property = Property::with('agent')->findOrFail($validated['property_id']);

        // 投稿者（未ログイン時はゲストユーザーに束ねる）
        $userId = auth()->id() ?? DB::table('users')->where('email','guest@example.com')->value('id');

        $inquiry = Inquiry::create([
            ...$validated,
            'user_id'  => $userId,
            'agent_id' => $property->agent_id,
            'status'   => 'new',
        ]);

        // メール送信（同期）
        $to = $property->agent?->email ?: config('mail.from.address');
        Mail::to($to)->send(new InquirySubmitted($inquiry));

        return back()->with('success','Inquiry sent successfully.');
    }

    public function success()
    {
        return Inertia::render('Inquiries/Success');
    }

    // 返信API（設計: PUT /api/inquiries/{id}/reply に相当）
    public function reply(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate(['agent_reply'=>['required','string','max:5000']]);
        $inquiry->update([
            'agent_reply' => $data['agent_reply'],
            'status'      => 'replied',
            'replied_at'  => now(),
        ]);

        // 返信通知を送るならここで Mail::to($inquiry->email)->send(...) など
        return back()->with('success','Reply saved.');
    }

    // テンプレート送信API
    public function replyWithTemplate(Request $request, Inquiry $inquiry, InquiryReplyService $replyService): RedirectResponse
    {
        $validated = $request->validate([
            'to_email'  => ['required', 'email', 'max:255'],
            'subject'   => ['required', 'string', 'max:500'],
            'body'      => ['required', 'string', 'max:5000'],
            'signature' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $log = $replyService->sendReply(
                $inquiry,
                $validated['to_email'],
                $validated['subject'], 
                $validated['body'],
                $validated['signature'] ?? null
            );

            return back()->with('success', 'Email sent successfully. Log ID: ' . $log->id);
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }
}