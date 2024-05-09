<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeamMembers extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $userId = $model->user_id;

            TeamInvites::where('to_user', $userId)->orWhere('from_user', $userId)->delete();

            NotificationInvites::where('from_user', $userId)->orWhere('user_id', $userId)->delete();

            if (static::where('team_id', $model->team_id)->count() >= 5) {
                TeamInvites::where('team_id', $model->team_id)->delete();
                NotificationInvites::where('team_id', $model->team_id)->delete();
            }
        });
    }



    public $timestamps = false;
    protected $fillable = [
        'team_id',
        'user_id',
    ];
}
