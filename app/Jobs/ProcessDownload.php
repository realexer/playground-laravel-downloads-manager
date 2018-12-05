<?php

namespace App\Jobs;

use App\DownloadsManager\Components\DownloadFailedException;
use App\DownloadsManager\Models\Download;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\DownloadsManager\Components\Downloader;
use App\DownloadsManager\Components\DownloadsManager;
use App\DownloadsManager\Models\Download\Status as DownloadStatus;

class ProcessDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\DownloadsManager\Models\Download
     */
    public $download;

    public $tries = 5;

    /**
     * ProcessDownload constructor.
     * @param Download $download
     */
    public function __construct(Download $download)
    {
        //
        $this->download = $download;
    }

    /**
     * @param Downloader $downloader
     * @param DownloadsManager $manager
     * @throws DownloadFailedException
     */
    public function handle(Downloader $downloader, DownloadsManager $manager)
    {
        $manager->updateStatus($this->download, DownloadStatus::PROCESSING, 'Processing started...');

        try 
        {
            $downloader->download($this->download->original_url, $this->download->filename);

            $manager->updateStatus($this->download, DownloadStatus::COMPLETED, 'Download completed.');
        }
        catch(DownloadFailedException $ex)
        {
            $manager->updateStatus($this->download, DownloadStatus::FAILED, "Downloading failed. Reason: '".$ex->getMessage()."'.");

            throw $ex;
        }
    }
}
