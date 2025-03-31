<?php

namespace Tests\Feature;

use App\Services\Upload\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\Request;

class ImageUploadServiceTest extends TestCase
{
    private ImageUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ImageUploadService();
    }

    public function test_successful_image_upload()
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600);
        $request = new Request();
        $request->files->set('file', $file);

        $response = $this->service->handle($request, 'file', 'images');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'src',
                    'mime_type',
                    'size',
                    'name',
                    'stored_name',
                    'path'
                ]
            ]);

        Storage::disk('s3')->assertExists('uploads/images/' . $response['data']['stored_name']);
    }

    public function test_fails_when_no_file_is_uploaded()
    {
        $request = new Request();

        $response = $this->service->handle($request, 'file', 'images');

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'error' => ['message' => 'No file was uploaded.']
            ]);
    }

    public function test_fails_when_invalid_file_type_is_uploaded()
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->create('invalid.txt', 100);
        $request = new Request();
        $request->files->set('file', $file);

        $response = $this->service->handle($request, 'file', 'images');

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'error' => ['message' => 'Invalid file type. Allowed types: image/jpeg, image/png, image/gif, image/webp, image/svg+xml']
            ]);
    }

    public function test_fails_when_file_exceeds_max_size()
    {
        Storage::fake('s3');

        // Criando um arquivo maior que 5MB
        $largeFile = UploadedFile::fake()->image('large.jpg')->size(6000);
        $request = new Request();
        $request->files->set('file', $largeFile);

        $response = $this->service->handle($request, 'file', 'images');

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'error' => ['message' => 'File too large. Maximum size: 5 MB']
            ]);
    }
}
