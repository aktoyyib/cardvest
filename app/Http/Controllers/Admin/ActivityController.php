<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('log_name', 'admin.transactions')->simplePaginate(40);
        return view('admin.activity.index', compact('activities'));
    }
}
