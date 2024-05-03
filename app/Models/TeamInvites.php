<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvites extends Model
{
    use HasFactory;


    public $timestamps = false;


    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $team = Team::find($model->team_id);

            $type = ($team->leader_id == $model->to_user) ? 'join' : 'invite';
            NotificationInvites::create([
                'team_id' => $model->team_id,
                'user_id' => $model->to_user,
                'from_user' => $model->from_user,
                'type' => $type,
            ]);
        });
        static::deleted(function ($model) {
            NotificationInvites::where('team_id', $model->team_id)->where('from_user', $model->from_user)->delete();
        });
    }

    protected $fillable = [
        'team_id',
        'to_user',
        'from_user',
    ];
}
