<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        return $this->hasManyThrough(User::class, TeamsMembers::class, 'team_id', 'id', 'id', 'user_id');
    }

    private static function generateInviteCode(): string
    {
        return strtoupper(Str::random(10));
    }
}
