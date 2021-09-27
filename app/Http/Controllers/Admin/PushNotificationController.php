<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function index()
    {
        return view('admin.push-notifications.index');
    }

    public function pushNotification(Request $request)
    {
        //
    }

    public function viewNotifications($id)
    {
        //
    }

}
