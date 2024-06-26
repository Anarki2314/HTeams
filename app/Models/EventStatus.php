<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title'];

    // public function events()
    // {
    //     return $this->hasMany(Event::class, 'status_id', 'id');
    // }


    public static function getByTitle($title)
    {
        return self::where('title', $title)->first();
    }
}
