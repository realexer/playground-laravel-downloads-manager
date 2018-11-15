<?php

namespace Tests\Feature\routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

use App\DownloadsManager\Components\DownloadsManager;
use App\DownloadsManager\Models\Download;

class WebTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDownloads_all()
    {
        $response = $this->get('/downloads');

        $response->assertStatus(200);
    }
    
    public function testDownloads_add_invalidUrl()
    {
        $response = $this->post('/downloads/add', [
            'url' => 'test'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('');
        $response->assertSessionHasErrors([]);
        $this->assertEquals(Session::get('errors')->toArray(), ['url' => [0  => 'The url format is invalid.']]);
    }
    
    public function testDownloads_add_validUrl()
    {
        $response = $this->call('POST', '/downloads/add', [
            'url' => 'http://google.com',
        ]);
            
        $response->assertStatus(302);
        $response->assertRedirect('downloads');
        $response->assertSessionHas('status');
    }

    public function testDownloads_download()
    {
        $url = "http://google.com";
        $download = DownloadsManager::addDownload($url);

        $job = new \App\Jobs\ProcessDownload(Download::where('id', $download->id)->first());
        $job->handle(new \App\DownloadsManager\Components\Downloader());
        
        $response = $this->get("/downloads/download/{$download->id}");
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', "attachment; filename={$download->filename}");
    }
}
