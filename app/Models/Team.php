<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->invite_code) {
                $model->invite_code = self::generateInviteCode();
            }
            if (!$model->leader_id) {
                $model->leader_id = auth()->user()->id;
            }
        });
    }

    protected $fillable = [
        'title',
        'invite_code',
        'leader_id',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, TeamMembers::class, 'team_id', 'id', 'id', 'user_id'); // teamMembers.team_id = teams.id and users.id = teamMembers.user_id
    }

    public function membersList(): HasMany
    {
        return $this->hasMany(TeamMembers::class, 'team_id', 'id');
    }

    public function invites(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, TeamInvites::class, 'team_id', 'id', 'id', 'from_user')->where('from_user', '!=', $this->leader_id);
    }

    public function invitesList(): HasMany
    {
        return $this->hasMany(TeamInvites::class, 'team_id', 'id');
    }

    private static function generateInviteCode(): string
    {
        return strtoupper(Str::random(10));
    }
}
