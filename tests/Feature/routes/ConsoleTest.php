<?php

namespace Tests\Feature\routes;

use App\DownloadsManager\Components\DownloadsManager;
use Tests\TestCase;

use App\DownloadsManager\Models\Download;

class ConsoleTest extends TestCase
{
    public function setUp() 
    {
        parent::setUp();

        // DISCLAIMER: 
        // is there a way to assert output after an artisan command was executed? 
        // Couldn't find one.
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDownloads_add_invalidUrl()
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = $this->artisan('downloads:add', ['url' => 'invalid']);
        $command->assertExitCode(-1);
    }
    
    public function testDownloads_add_validUrl()
    {
        Download::truncate();

        $command = $this->artisan('downloads:add', ['url' => 'http://google.com']);
        $command->assertExitCode(0);

    }

    public function testDownloads_get_notfound()
    {
        $id = 123;
        $command = $this->artisan('downloads:get', ['id' => $id]);
        $command->expectsOutput("Download with id '{$id}' not found.");
        $command->assertExitCode(0);
    }
    
    public function testDownloads_get()
    {
        $download = (new DownloadsManager())->addDownload("http://google.com");

        $command = $this->artisan('downloads:get', ['id' => $download->id]);
        $command->assertExitCode(0);
    }

    public function testDownloads_all()
    {
        $command = $this->artisan('downloads:all');
        $command->assertExitCode(0);
    }
}
