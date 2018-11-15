<?php

namespace Tests\Unit;

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

        $result = DownloadsManager::addDownload("invalid_url");

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
        $download = DownloadsManager::addDownload($url);
        
        $this->assertEquals($download->original_url, $url);
        $this->assertEquals($download->status, \App\DownloadsManager\Models\Download\Status::SUBMITTED);

        Queue::assertPushed(\App\Jobs\ProcessDownload::class, function ($job) use ($download) {
            return $job->download->id === $download->id;
        });
    }

    public function testAddDownload_validUrl_dispatch_unreachable()
    {
        $this->expectException(\App\DownloadsManager\Components\DownloadFailedException::class);

        $url = "http://google2.com";
        $download = DownloadsManager::addDownload($url);

        $job = new \App\Jobs\ProcessDownload(Download::where('id', $download->id)->first());
        $job->handle(new \App\DownloadsManager\Components\Downloader());

        $downloaded = DownloadsManager::getDownload($download->id);
        $this->assertEquals($downloaded->status, \App\DownloadsManager\Models\Download\Status::FAILED);

        $this->assertNull($downloaded->download_url);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddDownload_validUrl_dispatch_richable()
    {
        $url = "http://google.com";
        $download = DownloadsManager::addDownload($url);

        $job = new \App\Jobs\ProcessDownload(Download::where('id', $download->id)->first());
        $job->handle(new \App\DownloadsManager\Components\Downloader());

        $downloaded = DownloadsManager::getDownload($download->id);
        $this->assertEquals($downloaded->status, \App\DownloadsManager\Models\Download\Status::COMPLETED);

        $this->assertEquals($downloaded->download_url, \App\DownloadsManager\Components\DownloadsStorage::getUrl($download->filename));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddDownload_getAll()
    {
        Download::truncate();
        \App\DownloadsManager\Models\DownloadsLog::truncate();

        DownloadsManager::addDownload("http://google.com");
        DownloadsManager::addDownload("http://google2.com");

        $allDownloads = DownloadsManager::getAll();

        $this->assertEquals(count($allDownloads->collection), 2);
        $this->assertEquals($allDownloads->collection[0]->original_url, "http://google2.com");
        $this->assertEquals($allDownloads->collection[1]->original_url, "http://google.com");
    }
}
