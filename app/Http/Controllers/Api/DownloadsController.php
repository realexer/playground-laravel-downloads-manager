<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Download as DownloadResource;
use App\DownloadsManager\Models\Download;
use App\DownloadsManager\Components\DownloadsManager;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DownloadsManager $manager
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all(DownloadsManager $manager)
    {
        return $manager->getAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param DownloadsManager $manager
     * @return DownloadResource
     */
    public function add(Request $request, DownloadsManager $manager)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        return $manager->addDownload($request->url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param DownloadsManager $manager
     * @return DownloadResource
     */
    public function get(int $id, DownloadsManager $manager)
    {
        return $manager->getDownload($id);
    }
}
