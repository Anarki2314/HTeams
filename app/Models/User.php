<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'orgName',
        'phone',
        'email',
        'password',
        'role_id',
        'avatar_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function invites(): HasManyThrough
    {
        return $this->hasManyThrough(Team::class, TeamInvites::class, 'to_user', 'id', 'id', 'team_id')->where('to_user', '=', $this->id);
    }



    public function team(): HasOneThrough
    {
        return $this->hasOneThrough(Team::class, TeamMembers::class, 'user_id', 'id', 'id', 'team_id'); // teamMembers.user_id = user.id and teamMembers.team_id = team.id
    }

    public function isLeader(): bool
    {
        return $this->id === $this->team?->leader_id;
    }

    public function isAdmin(): bool
    {
        return $this->role->title === 'Админ';
    }

    public function isUser(): bool
    {
        return $this->role->title === 'Пользователь';
    }

    public function isOrganizer(): bool
    {
        return $this->role->title === 'Организатор';
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(File::class, 'avatar_id');
    }

    public function generateAvatar()
    {
        $avatar = File::generateAvatar();
        $this->attributes['avatar_id'] = $avatar->id;
        $this->save();
        return $avatar;
    }

    public function changePassword($oldPassword, $newPassword)
    {
        if (Hash::check($oldPassword, $this->password)) {
            $this->password = $newPassword;
            $this->save();
            return true;
        } else {
            return false;
        }
    }


    public function isBanned(): bool
    {
        return Ban::where('user_id', $this->id)->where('expires_at', '>', now())->exists();
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }
}
