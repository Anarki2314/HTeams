<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPrizes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'prize',
        'place'
    ];


    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public static function insertPrizes($event_id, $prizes)
    {
        return self::insert([
            [

                'event_id' => $event_id,
                'prize' => $prizes['firstPlace'],
                'place' => 1
            ],

            [
                'event_id' => $event_id,
                'prize' => $prizes['secondPlace'],
                'place' => 2
            ],

            [
                'event_id' => $event_id,
                'prize' => $prizes['thirdPlace'],
                'place' => 3
            ],

        ]);
    }
}
