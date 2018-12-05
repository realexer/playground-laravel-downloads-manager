<?php

namespace Tests\Unit;

use App\DownloadsManager\Components\Downloader;
use App\DownloadsManager\Components\DownloadFailedException;
use App\DownloadsManager\Components\DownloadsStorage;
use App\DownloadsManager\Models\DownloadsLog;
use App\Jobs\ProcessDownload;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\DownloadsManager\Components\DownloadsManager;
use App\DownloadsManager\Models\Download;

class DownloadsManagerTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp() 
    {
        parent::setUp();

        $this->refreshDatabase();

        Queue::fake();
    }
    
    public function testAddDownload_invalidUrl() 
    {
        $this->expectException(\InvalidArgumentException::class);

        $result = (new DownloadsManager())->addDownload("invalid_url");

        $this->assertNull($result);
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddDownload_validUrl_queue()
    {
        $url = "http://google.com";
        $download = (new DownloadsManager())->addDownload($url);
        
        $this->assertEquals($download->original_url, $url);
        $this->assertEquals($download->status, Download\Status::SUBMITTED);

        Queue::assertPushed(ProcessDownload::class, function ($job) use ($download) {
            return $job->download->id === $download->id;
        });
    }

    public function testAddDownload_validUrl_dispatch_unreachable()
    {
        $this->expectException(DownloadFailedException::class);

        $url = "http://google2.com";
        $download = (new DownloadsManager())->addDownload($url);

        $job = new ProcessDownload(Download::where('id', $download->id)->first());
        $job->handle(new Downloader(new DownloadsStorage()), new DownloadsManager());

        $downloaded = (new DownloadsManager())->getDownload($download->id);
        $this->assertEquals($downloaded->status, Download\Status::FAILED);

        $this->assertNull($downloaded->download_url);
    }

    /**
     * @throws \App\DownloadsManager\Components\DownloadFailedException
     */
    public function testAddDownload_validUrl_dispatch_richable()
    {
        $url = "http://google.com";
        $download = (new DownloadsManager())->addDownload($url);

        $job = new ProcessDownload(Download::where('id', $download->id)->first());
        $job->handle(new Downloader(new DownloadsStorage()), new DownloadsManager());

        $downloaded = (new DownloadsManager())->getDownload($download->id);
        $this->assertEquals($downloaded->status, Download\Status::COMPLETED);

        $this->assertEquals($downloaded->download_url, (new DownloadsStorage())->getUrl($download->filename));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddDownload_getAll()
    {
        $manager = new DownloadsManager();

        Download::truncate();
        DownloadsLog::truncate();

        $manager->addDownload("http://google.com");
        $manager->addDownload("http://google2.com");

        $allDownloads = (new DownloadsManager())->getAll();

        $this->assertEquals(count($allDownloads->collection), 2);
        $this->assertEquals($allDownloads->collection[0]->original_url, "http://google2.com");
        $this->assertEquals($allDownloads->collection[1]->original_url, "http://google.com");
    }
}
