<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventFullResource;
use App\Models\Event;
use App\Models\EventPrizes;
use App\Models\EventStatus;
use App\Models\EventTags;
use App\Models\File;
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
        $imageFile = $request->file('image');
        $taskFile = $request->file('task');
        $imagePath = File::fileUpload($imageFile);
        $taskPath = File::fileUpload($taskFile);
        $validated['creator_id'] = $request->user()->id;
        $validated['status_id'] = EventStatus::getByTitle('На проверке')->id;

        $imageCreated = File::create(['name' => $imageFile->getClientOriginalName(), 'path' => $imagePath]);
        $taskCreated = File::create(['name' => $taskFile->getClientOriginalName(), 'path' => $taskPath]);
        $validated['image_id'] = $imageCreated->id;
        $validated['task_id'] = $taskCreated->id;


        $event = Event::create($validated);

        $tags = EventTags::insertTags($event->id, $validated['tags']);
        $prizes = EventPrizes::insertPrizes($event->id, $validated['prizes']);



        return response()->json(['message' => 'Соревнование успешно создано', 'data' => [
            'id' => $event->id
        ]], 201);
    }

    public function getFullEvent(Request $request, $id)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            $event = Event::where(['status_id' => EventStatus::getByTitle('На проверке')->id])->findOrFail($id);
        } else {
            $event = Event::where(['creator_id' => $user->id])->findOrFail($id);
        }


        return response()->json([
            'data' =>  new EventFullResource($event),

        ], 200);
    }

    public function updateEvent(UpdateEventRequest $request, $id)
    {

        $validated = $request->validated();
        $validated['status_id'] = EventStatus::getByTitle('На проверке')->id;
        $event = Event::where(['status_id' => [EventStatus::getByTitle('На проверке')->id, EventStatus::getByTitle('Новое')->id], 'creator_id' => $request->user()->id])->findOrFail($id);
        $event->update($validated);

        return response()->json(['message' => 'Соревнование успешно обновлено']);
    }

    public function cancelEvent(Request $request, $id)
    {

        $user = $request->user();
        $event = Event::where(['creator_id' => $user->id, 'status_id' => [EventStatus::getByTitle('На проверке')->id, EventStatus::getByTitle('Новое')->id, EventStatus::getByTitle('Регистрация')->id]])->findOrFail($id);
        $event->status_id = EventStatus::getByTitle('Отменено')->id;
        $event->save();


        return response()->json(['message' => 'Соревнование отменено']);

        // Maybe send notifications to all registrated users
    }
}
