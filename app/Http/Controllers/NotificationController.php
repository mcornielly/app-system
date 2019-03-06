<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function get()
    {
    	// return Notification::all();
    	// return Auth::user()->notifications;
    	$unreadNotifications = Auth::user()->unreadNotifications;
    	
    	foreach ($unreadNotifications as $notification) {
    		$dateNow = date('Y-m-d');
    		if($dateNow != $notification->created_at->toDateString()){
    			$notification->markAsRead();
    		}
    	}

    	return Auth::user()->unreadNotifications;
    }
}
