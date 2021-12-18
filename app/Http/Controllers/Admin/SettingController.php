<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        $cedis_to_naira = Setting::where('key', 'cedis_to_naira')->first();

        return view('admin.settings.index', compact('cedis_to_naira'));
    }

    public function store(Request $request) {
        // return $request->except('_token');

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // OR
        // Setting::upsert([
        //     // ['key' => $key, 'value' => $value],
        //     // ['key' => $key, 'value' => $value],
        //     // ['key' => $key, 'value' => $value],
        // ], ['key'], ['value']);

        return back()->with('success', 'Settings updated!');
    }
}
