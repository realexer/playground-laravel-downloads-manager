<?php 

namespace App\DownloadsManager\Components;

use Illuminate\Support\Facades\Storage;

class DownloadsStorage 
{
    public static function put($name, $content) 
    {
        Storage::disk('local')->put($name, $content);
    }
    
    public static function get($name) 
    {
        Storage::disk('local')->get($name);
    }

    public static function getUrl($name) 
    {
        return Storage::url($name);
    }
    
    public static function download($name) 
    {
        return Storage::download($name);
    }
}