<?php

namespace App\Http\Controllers;

use App\DownloadsManager\Components\DownloadsManager;
use App\Jobs\ProcessDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DownloadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $downloads = DownloadsManager::getAll();

        return view('downloads.index', [
            'downloads' => $downloads->collection
        ]);
    }

    public function add(Request $request) 
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        DownloadsManager::addDownload($request->url);

        return redirect('downloads')->with('status', 'Url submitted.');
    }

    public function download($id) 
    {
        $download = DownloadsManager::getDownload($id);

        if($download) 
        {
            return \App\DownloadsManager\Components\DownloadsStorage::download($download->filename);
        }
        else 
        {
            throw new \Exception("Not found or not ready yet.");
        }
    }
}
