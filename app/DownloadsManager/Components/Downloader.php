<?php 

namespace App\DownloadsManager\Components;

class Downloader implements Contract\DownloaderInterface
{
    private $storage;

    public function __construct(DownloadsStorage $storage)
    {
        $this->storage = $storage;
    }

    public function download(string $url, string $name)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);

        $error = curl_error($curl);

        curl_close($curl);

        if($error) {
            throw new DownloadFailedException("Downloading of '{$url}' failed. Details: '{$error}'.");
        }

        $this->storage->put($name, $content);
    }
}

class DownloadFailedException extends \Exception 
{

}
