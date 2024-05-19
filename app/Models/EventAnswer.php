<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAnswer extends Model
{
    use HasFactory;


    protected $fillable = [
        'event_id',
        'team_id',
        'answer_id',
    ];


    public function answer(): BelongsTo
    {
        return $this->belongsTo(File::class, 'answer_id', 'id');
    }


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }


    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
