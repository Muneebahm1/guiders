<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->limit(200)->get();

        return view('activity.index', compact('logs'));
    }
}
