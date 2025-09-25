<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InquiriesController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $inquiries = $user->inquiries() // relation 名に合わせてください
            ->with(['property'])
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return inertia('User/Inquiries', [
            'inquiries' => $inquiries
        ]);
    }
}