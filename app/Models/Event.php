<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'description',
        'status_id',
        'creator_id',
        'date_registration',
        'date_start',
        'date_end',

    ];


    public function status(): BelongsTo
    {
        return $this->belongsTo(EventStatus::class, 'status_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
