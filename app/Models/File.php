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


    public static function generateAvatar()
    {
        $url = 'https://robohash.org/' . \Illuminate\Support\Str::random(10) . '?set=set3';

        try {

            $fileName = uniqid() . '.png';
            $path = public_path('assets/uploads/');
            $fileContents = file_get_contents($url);
            file_put_contents($path . $fileName, $fileContents);
            $file = File::create(['name' => $fileName, 'path' => '/assets/uploads/' . $fileName]);
            return $file->id;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
