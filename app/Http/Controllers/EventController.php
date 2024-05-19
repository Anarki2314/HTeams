<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventFullResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\FileResource;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\EventPrizes;
use App\Models\EventStatus;
use App\Models\EventTags;
use App\Models\EventTeams;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File as RulesFile;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{

    public function index(Request $request)
    {

        $events = QueryBuilder::for(Event::class)
            ->select(['id', 'title', 'date_registration', 'updated_at', 'image_id', 'creator_id', 'status_id'])
            ->defaultSort('-updated_at')
            ->with('image', function ($query) {
                $query->select(['id', 'name', 'path']);
            })
            ->with('status')
            ->with('creator', function ($query) {
                $query->select(['id', 'orgName']);
            })
            ->with('tags', function ($query) {
                $query->select(['tags.id', 'tags.title']);
            })
            ->allowedSorts(['updated_at', 'title', 'date_registration', 'date_start'])
            ->allowedFilters([
                'title',
                AllowedFilter::custom('tags', new \App\Filters\TagsFilter()),
                AllowedFilter::exact('statuses', 'status_id'),
            ],)
            ->where('status_id', '!=', EventStatus::getByTitle('Отменено')->id)
            ->where('status_id', '!=', EventStatus::getByTitle('На проверке')->id)
            ->paginate($request->get('perPage', 10));
        return response()->json($events);
    }

    public function show(Request $request, $id)
    {
        $event = Event::where('status_id', '!=', EventStatus::getByTitle('Отменено')->id)->where('status_id', '!=', EventStatus::getByTitle('На проверке')->id)->findOrFail($id);
        return response()->json([
            'data' => new EventResource($event)
        ]);
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

    public function getModerationEvents(Request $request)
    {
        $events = QueryBuilder::for(Event::class)
            ->select(['id', 'title', 'date_registration', 'created_at', 'updated_at', 'image_id', 'creator_id'])
            ->defaultSort('-updated_at')
            ->with('image', function ($query) {
                $query->select(['id', 'name', 'path']);
            })
            ->with('creator', function ($query) {
                $query->select(['id', 'orgName']);
            })
            ->with('tags', function ($query) {
                $query->select(['tags.id', 'tags.title']);
            })
            ->where(['status_id' => EventStatus::getByTitle('На проверке')->id])
            ->allowedSorts(['created_at', 'updated_at'])
            ->allowedFilters(['title', AllowedFilter::custom('tags', new \App\Filters\TagsFilter())])
            ->paginate($request->get('perPage', 10));


        return response()->json($events);
    }

    public function cancelEvent(Request $request, $id)
    {

        $user = $request->user();
        if ($user->isAdmin()) {
            $event = Event::where(['status_id' => EventStatus::getByTitle('На проверке')->id])->findOrFail($id);
        } else {
            $event = Event::where(['creator_id' => $user->id])->whereIn('status_id', [EventStatus::getByTitle('На проверке')->id, EventStatus::getByTitle('Новое')->id, EventStatus::getByTitle('Регистрация')->id])->findOrFail($id);
        }
        $event->status_id = EventStatus::getByTitle('Отменено')->id;
        $event->save();


        return response()->json(['message' => 'Соревнование отменено']);

        // Maybe send notifications to all registrated users
    }

    public function approveEvent(Request $request, $id)
    {

        $user = $request->user();
        $event = Event::where(['status_id' => EventStatus::getByTitle('На проверке')->id])->findOrFail($id);


        $event->status_id = EventStatus::getByTitle('Новое')->id;
        $event->save();


        return response()->json(['message' => 'Соревнование одобрено']);
    }


    public function joinEvent(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->isLeader()) {
            return response()->json(['message' => 'Зарегистрироваться может только лидер команды']);
        }
        $event = Event::where(['status_id' => EventStatus::getByTitle('Регистрация')->id])->findOrFail($id);

        EventTeams::create([
            'event_id' => $event->id,
            'team_id' => $user->team->id,
        ]);
        return response()->json(['message' => 'Вы успешно зарегистрировались на соревнование']);
    }


    public function leaveEvent(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->isLeader()) {
            return response()->json(['message' => 'Отменить регистрацию может только лидер команды']);
        }

        $event = Event::where(['status_id' => EventStatus::getByTitle('Регистрация')->id])->findOrFail($id);


        EventTeams::where(['event_id' => $event->id, 'team_id' => $user->team->id])->delete();

        return response()->json(['message' => 'Вы отменили регистрацию на соревнование']);
    }

    public function answerEvent(Request $request, $id)
    {
        $validated = $request->validate([
            'answer' => [
                'required',
                RulesFile::types(['doc', 'docx', 'pdf'])->min('1kb')->max('4mb')
            ],
        ]);
        $user = $request->user();
        if (!$user->isLeader()) {
            return response()->json(['message' => 'Прикрепить ответ может только лидер команды']);
        }


        $event = Event::where(['status_id' => EventStatus::getByTitle('Началось')->id])->findOrFail($id);

        $answerFile = $request->file('answer');
        $answerPath = File::fileUpload($answerFile);
        $answerCreated = File::create(['name' => $answerFile->getClientOriginalName(), 'path' => $answerPath]);

        $eventAnswer = EventAnswer::where(['event_id' => $event->id, 'team_id' => $user->team->id])->first();
        if ($eventAnswer) {
            $eventAnswer->answer_id = $answerCreated->id;
            $eventAnswer->save();
            return response()->json(['message' => 'Ответ успешно прикреплен', 'data' => [
                'answer' => new FileResource($answerCreated),
            ]], 201);
        }
        EventAnswer::create([
            'event_id' => $event->id,
            'team_id' => $user->team->id,
            'answer_id' => $answerCreated->id,
        ]);
        return response()->json(['message' => 'Ответ успешно прикреплен', 'data' => [
            'answer' => new FileResource($answerCreated),
        ]], 201);
    }
}
