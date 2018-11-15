<?php

use Illuminate\Foundation\Inspiring;
use App\DownloadsManager\Components\DownloadsManager;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('downloads:all', function() {
    $this->line(DownloadsManager::getAll()->toJson());
});

Artisan::command('downloads:add {url}', function($url) 
{
    $this->line(DownloadsManager::addDownload($url)->toJson());
});

Artisan::command('downloads:get {id}', function($id) 
{
    $download = DownloadsManager::getDownload($id);

    // TODO: is it a best way to check whether resource was set?
    if($download->resource) {
        $this->line($download->toJson());
    } else {
        $this->line("Download with id '{$id}' not found.");
    }
});