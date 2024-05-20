<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationEvents extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_id',
        'type',
        'message',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public static function insertNotification($userId, $eventId, $type, $message = null)
    {
        $notification = new NotificationEvents();
        $notification->user_id = $userId;
        $notification->event_id = $eventId;
        $notification->type = $type;
        $notification->message = $message;
        $notification->save();
    }
}
