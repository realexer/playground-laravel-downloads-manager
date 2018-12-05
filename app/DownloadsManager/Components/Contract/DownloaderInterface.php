<?php
/**
 * Created by PhpStorm.
 * User: alexeyskrypnik
 * Date: 12/5/18
 * Time: 1:34 PM
 */

namespace App\DownloadsManager\Components\Contract;


interface DownloaderInterface
{
    public function download(string $url, string $name);
}
