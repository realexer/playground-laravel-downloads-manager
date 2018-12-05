<?php
/**
 * Created by PhpStorm.
 * User: alexeyskrypnik
 * Date: 12/5/18
 * Time: 1:35 PM
 */

namespace App\DownloadsManager\Components\Contract;


interface DownloadsStorageInterface
{
    public function put(string $name, string $content);

    public function get(string $name);

    public function getUrl(string $name);

    public function download(string $name);
}
