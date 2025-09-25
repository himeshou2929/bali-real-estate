<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    public function index()
    {
        return inertia('Admin/AuditTrails/Index',[
            'logs'=>AuditTrail::latest()->take(100)->get()
        ]);
    }
}