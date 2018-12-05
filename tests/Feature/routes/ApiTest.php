<?php

namespace Tests\Feature\routes;

use Tests\TestCase;

use App\DownloadsManager\Models\Download;
use App\Http\Resources\Download as DownloadResource;

class ApiTest extends TestCase
{
    public function setUp() 
    {
        parent::setUp();
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDownloads_add_invalidUrl()
    {
        $response = $this->json('POST', '/api/downloads/add', [
            'url' => 'test'
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'errors' => [
                        'url' => ['The url format is invalid.'],
                    ],
                    'message' => 'The given data was invalid.'
                ]);
    }
    
    public function testDownloads_add_validUrl()
    {
        Download::truncate();

        $response = $this->json('POST', '/api/downloads/add', [
            'url' => 'http://google.com'
        ]);

        $download = new DownloadResource(Download::first());

        $donwloadAr = $download->toArray( new \Illuminate\Http\Request());
        $donwloadAr['status'] = \App\DownloadsManager\Models\Download\Status::SUBMITTED;

        $response->assertStatus(201)
                    ->assertExactJson([
                        'data' => $donwloadAr
                    ]);
    }

    public function testDownloads_get()
    {
        Download::truncate();

        $this->json('POST', '/api/downloads/add', [
            'url' => 'http://google.com'
        ]);

        $download = new DownloadResource(Download::first());

        $response = $this->get("/api/downloads/{$download->id}");

        $donwloadAr = $download->toArray( new \Illuminate\Http\Request());

        $response->assertStatus(200)
                    ->assertExactJson([
                        'data' => $donwloadAr
                    ]);
    }

    public function testDownloads_all()
    {
        Download::truncate();

        $this->json('POST', '/api/downloads/add', [
            'url' => 'http://google.com'
        ]);

        $download = new DownloadResource(Download::first());

        $response = $this->get("/api/downloads/all");

        $donwloadAr = $download->toArray( new \Illuminate\Http\Request());

        $response->assertStatus(200)
                    ->assertExactJson([
                        'data' => [$donwloadAr]
                    ]);
    }
}
