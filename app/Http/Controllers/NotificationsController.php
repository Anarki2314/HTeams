<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationEventsResource;
use App\Http\Resources\NotificationInvitesResource;
use App\Models\Event;
use App\Models\EventStatus;
use App\Models\NotificationEvents;
use App\Models\NotificationInvites;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = $request->user();

        $responseData = [];
        $count = 0;
        $invites = NotificationInvites::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $responseData['invites'] = NotificationInvitesResource::collection($invites);

        $count += $invites->count();

        $events = NotificationEvents::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $responseData['events'] = NotificationEventsResource::collection($events);

        $count += $events->count();
        return response()->json([
            'data' => $responseData,
            'count' => $count
        ], 200);
    }

    public function deleteNotification(Request $request, $id)
    {
        $user = $request->user();
        $notification = NotificationEvents::where('user_id', $user->id)->findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'Уведомление удалено'], 200);
    }

    public function getStatsInfo(Request $request)
    {
        $userCount = User::where('role_id', '!=', Role::getIdRoleByTitle('Админ'))->count();
        $teamsCount = Team::get()->count();
        $eventsCount = Event::where('status_id', '!=', EventStatus::getByTitle('Отменено')->id)->where('status_id', '!=', EventStatus::getByTitle('На проверке')->id)->count();
        return response()->json([
            'data' => [
                'users' => $userCount,
                'teams' => $teamsCount,
                'events' => $eventsCount
            ]
        ], 200);
    }
}
