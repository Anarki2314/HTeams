<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationInvites extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'team_id', 'from_user'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
