<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Tag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title'];

    // public function events(): HasManyThrough
    // {
    // return $this->hasManyThrough(Event::class, EventTag::class, 'tag_id', 'id', 'id', 'event_id');
    // }
}
