<?php

namespace App\Http\Resources;

use App\DownloadsManager\Components\DownloadsStorage;
use App\DownloadsManager\Models\Download\Status as DownloadStatus;
use App\DownloadsManager\Models\Download as DownloadModel;

use Illuminate\Http\Resources\Json\JsonResource;

class Download extends JsonResource
{
    public $download_url;

    public function __construct(DownloadModel $download = null)
    {
        parent::__construct($download);

        $storage = resolve('DownloadsManager\Storage');
        
        if($download && $download->status == DownloadStatus::COMPLETED) 
        {
            $this->download_url = $storage->getUrl($download->filename);
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
