<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Models\Event;
use App\Models\EventStatus;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function createEvent(CreateEventRequest $request)
    {

        $validated = $request->validated();
        var_dump($validated);
        die;
        $validated['creator_id'] = $request->user()->id;
        // $validated['status_id'] = EventStatus::getByTitle('Новое')->id;

        var_dump($validated);
        // $event = Event::create($validated);

        return response()->json(['message' => 'Соревнование успешно создано']);
    }
}
