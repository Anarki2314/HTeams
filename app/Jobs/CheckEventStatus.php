<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\EventStatus;
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
            'Finished' => EventStatus::getByTitle('Завершено')->id
        ];
        $events = Event::where('status_id', array_slice($statusesId, 0, 3))->get();

        foreach ($events as $event) {
            switch ($event->status_id) {
                case $statusesId['New']:
                    if ($event->date_registration < now('Europe/Moscow')) {
                        $event->status_id = $statusesId['Registration'];
                        $event->save();
                    }
                    break;
                case $statusesId['Registration']:
                    if ($event->date_start < now()) {
                        $event->status_id = $statusesId['Started'];
                        $event->save();
                    }
                    break;
                case $statusesId['Started']:
                    if ($event->date_end < now()) {
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
