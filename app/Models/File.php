<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'path'];


    public static function fileUpload(UploadedFile $file)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->move(public_path('assets/uploads'), $fileName);
        return '/assets/uploads/' . $fileName;
    }
}
