<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventResult extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'team_name', 'place'];

    public function team()
    {
        return Team::where('title', $this->team_name)->first();
    }
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
