<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reason', 'expires_at'];


    public function getExpiresAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isBanned(): bool
    {
        return $this->expires_at > now();
    }



    public static function banUser(User $user, string $reason, ?\DateTimeInterface $expiresAt)
    {
        $ban = self::where('user_id', $user->id)->first();
        if ($ban) {
            return $ban->update(['reason' => $reason, 'expires_at' => $expiresAt]);
        } else {
            return self::create([
                'user_id' => $user->id,
                'reason' => $reason,
                'expires_at' => $expiresAt,
            ]);
        }
    }

    public static function banUsersByTeam(Team $team, string $reason, ?\DateTimeInterface $expiresAt)
    {
        $team->members->each(function (User $user) use ($reason, $expiresAt) {
            self::banUser($user, $reason, $expiresAt);
        });
    }




    public static function unbanUser(User $user)
    {
        $ban = self::where('user_id', $user->id)->first();

        if ($ban) {
            return $ban->update(['expires_at' => now()]);
        } else {
            return null;
        }
    }

    public static function unbanUsersByTeam(Team $team)
    {
        $team->members->each(function (User $user) {
            self::unbanUser($user);
        });
    }
}
