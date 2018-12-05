<?php
/**
 * Created by PhpStorm.
 * User: alexeyskrypnik
 * Date: 12/5/18
 * Time: 1:35 PM
 */

namespace App\DownloadsManager\Components\Contract;


use App\DownloadsManager\Models\Download;

interface DownloadsManagerInterface
{
    public function getAll();

    public function addDownload(string $url);

    public function updateStatus(Download $download, string $status, string $message);

    public function getDownload(int $id);
}
