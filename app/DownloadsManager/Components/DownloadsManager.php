<?php

namespace App\DownloadsManager\Components;

use App\DownloadsManager\Models\Download;
use App\Http\Resources\Download as DownloadResource;
use App\DownloadsManager\Models\DownloadsLog;
use App\Jobs\ProcessDownload;
use App\DownloadsManager\Models\Download\Status as DownloadStatus;


class DownloadsManager implements Contract\DownloadsManagerInterface
{
    public function getAll()
    {
        return DownloadResource::collection(Download::orderBy('id', 'desc')->get());
    }

    public function addDownload(string $url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("Submitted url '{$url}' is not valid.");
        }

        $download = new Download();
        // TODO: check for duplicates
        $download->filename = \Illuminate\Support\Str::random(20);
        $download->original_url = $url;

        $this->updateStatus($download, DownloadStatus::SUBMITTED, 'Request for download submitted.');

        ProcessDownload::dispatch($download);

        return new DownloadResource($download);
    }

    public function updateStatus(Download $download, string $status, string $message)
    {
        $download->status = $status;
        $download->save();

        $downloadsLog = new DownloadsLog();
        $downloadsLog->download_id = $download->id;
        $downloadsLog->download_status = $download->status;
        $downloadsLog->message = $message;

        $downloadsLog->save();

        return new DownloadResource($download);
    }

    public function getDownload(int $id)
    {
        return new DownloadResource(Download::where('id', $id)->first());
    }
}
