<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationInvitesResource;
use App\Models\NotificationInvites;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = $request->user();


        $responseData = [];
        $count = 0;
        $invites = NotificationInvites::where('user_id', $user->id)->get();

        $responseData['invites'] = NotificationInvitesResource::collection($invites);

        $count += $invites->count();


        return response()->json([
            'data' => $responseData,
            'count' => $count
        ], 200);
    }
}
