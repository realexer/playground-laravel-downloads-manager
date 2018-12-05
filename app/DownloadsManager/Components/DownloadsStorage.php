<?php 

namespace App\DownloadsManager\Components;

use Illuminate\Support\Facades\Storage;

class DownloadsStorage implements Contract\DownloadsStorageInterface
{
    public function put(string $name, string $content)
    {
        Storage::disk('local')->put($name, $content);
    }
    
    public function get(string $name)
    {
        Storage::disk('local')->get($name);
    }

    public function getUrl(string $name)
    {
        return Storage::url($name);
    }
    
    public function download(string $name)
    {
        return Storage::download($name);
    }
}
