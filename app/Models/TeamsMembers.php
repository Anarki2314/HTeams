<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeamsMembers extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'team_id',
        'user_id',
    ];
}
