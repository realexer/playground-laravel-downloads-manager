<?php

namespace App\Http\Resources;

use App\DownloadsManager\Models\Download\Status as DownloadStatus;

use Illuminate\Http\Resources\Json\JsonResource;

class Download extends JsonResource
{
    public $download_url;

    public function __construct(\App\DownloadsManager\Models\Download $download = null) 
    {
        parent::__construct($download);
        
        if($download && $download->status == DownloadStatus::COMPLETED) 
        {
            $this->download_url = \App\DownloadsManager\Components\DownloadsStorage::getUrl($download->filename);
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
