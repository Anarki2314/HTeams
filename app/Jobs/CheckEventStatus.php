<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\EventStatus;
use App\Models\EventTeams;
use App\Models\NotificationEvents;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEventStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $statusesId = [
            'New' => EventStatus::getByTitle('Новое')->id,
            'Registration' => EventStatus::getByTitle('Регистрация')->id,
            'Started' => EventStatus::getByTitle('Началось')->id,
            'Finished' => EventStatus::getByTitle('Завершено')->id,
            'Cancelled' => EventStatus::getByTitle('Отменено')->id
        ];
        $events = Event::whereIn('status_id', array_slice($statusesId, 0, 3))->get();

        foreach ($events as $event) {
            switch ($event->status_id) {
                case $statusesId['New']:
                    if ($event->date_registration < now('Europe/Moscow')) {

                        $event->status_id = $statusesId['Registration'];
                        $event->save();
                    }
                    break;
                case $statusesId['Registration']:
                    if ($event->date_start < now('Europe/Moscow')) {
                        $count = EventTeams::where('event_id', 1)->distinct('team_id')->count();
                        if ($count < 10) {
                            $event->status_id = $statusesId['Cancelled'];
                            $event->participants->each(function ($participant) use ($event) {
                                NotificationEvents::insertNotification($participant->user_id, $event->id, 'cancelled', 'Недостаточно участников');
                            });
                            NotificationEvents::insertNotification($event->creator_id, $event->id, 'cancelled', 'Недостаточно участников');
                            $event->save();


                            break;
                        }

                        $event->status_id = $statusesId['Started'];

                        $count = EventTeams::where('event_id', 1)->distinct('team_id')->count();
                        $event->participants->each(function ($participant) use ($event) {
                            NotificationEvents::insertNotification($participant->user_id, $event->id, 'started');
                        });
                        NotificationEvents::insertNotification($event->creator_id, $event->id, 'started');
                        $event->save();
                    }
                    break;
                case $statusesId['Started']:
                    if ($event->date_end < now('Europe/Moscow')) {
                        $event->participants->each(function ($participant) use ($event) {
                            NotificationEvents::insertNotification($participant->user_id, $event->id, 'finished');
                        });
                        NotificationEvents::insertNotification($event->creator_id, $event->id, 'finished');
                        $event->status_id = $statusesId['Finished'];
                        $event->save();
                    }
                    break;

                default:
                    break;
            }
        }
    }
}
