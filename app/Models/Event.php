<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        'image_id',
        'task_id',
    ];

    public function setDateRegistrationAttribute($value)
    {
        $this->attributes['date_registration'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = htmlspecialchars($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = htmlspecialchars($value);
    }
    public function getDateRegistrationAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    public function getDateEndAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(EventStatus::class, 'status_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id', 'id');
    }


    public function tags(): HasManyThrough
    {
        return $this->hasManyThrough(Tag::class, EventTags::class, 'event_id', 'id', 'id', 'tag_id');
    }



    public function participants(): HasMany
    {
        return $this->hasMany(EventTeams::class, 'event_id', 'id');
    }


    public function prizes(): HasMany
    {
        return $this->hasMany(EventPrizes::class, 'event_id', 'id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(File::class, 'task_id', 'id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(EventResult::class, 'event_id', 'id');
    }
    public function answers(): HasMany
    {
        return $this->hasMany(EventAnswer::class, 'event_id', 'id')->orderBy('updated_at', 'desc');
    }

    public function isJoined(User $user): bool
    {
        return $this->participants->where('user_id', $user->id)->where('team_id', $user->team->id)->isNotEmpty();
    }
}
