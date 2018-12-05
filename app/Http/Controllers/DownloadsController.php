<?php

namespace App\Http\Controllers;

use App\DownloadsManager\Components\DownloadsManager;
use App\DownloadsManager\Components\DownloadsStorage;
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
     * @param DownloadsManager $manager
     * @return \Illuminate\Http\Response
     */
    public function index(DownloadsManager $manager)
    {
        $downloads = $manager->getAll();

        return view('downloads.index', [
            'downloads' => $downloads->collection
        ]);
    }

    public function add(Request $request, DownloadsManager $manager)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $manager->addDownload($request->url);

        return redirect('downloads')->with('status', 'Url submitted.');
    }

    public function download($id, DownloadsManager $manager, DownloadsStorage $storage)
    {
        $download = $manager->getDownload($id);

        if($download) 
        {
            return $storage->download($download->filename);
        }
        else 
        {
            throw new \Exception("Not found or not ready yet.");
        }
    }
}
