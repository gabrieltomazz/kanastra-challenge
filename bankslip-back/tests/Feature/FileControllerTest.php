<?php

namespace Tests\Feature;

use App\Models\File;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

use App\Jobs\ProcessCsv;

class FileControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        // mock test files
        $file = File::factory()->create();

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'name' => $file->name,
            'status' => $file->status,
        ]);

        // Send a GET request to the /files endpoint
        $response = $this->getJson('/files');

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'status',
                'created_at',
                'updated_at',
                'created',
                'updated',
                'processing_time'
            ]
        ]);
    }

    /**
     * Test the uploadCsv.
     *
     * @return void
     */
    public function testUploadCsv()
    {
        Queue::fake();
        Storage::fake('local');

        // Create a fake CSV file
        $file = UploadedFile::fake()->create('test.csv', 1024, 'text/csv');

        // Send a POST request to the /api/upload-csv endpoint with the fake file
        $response = $this->postJson('/upload-csv', [
            'file' => $file,
        ]);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response message
        $response->assertJson(['message' => 'CSV processed successfully']);

        // Assert a file record was created
        $this->assertDatabaseHas('files', [
            'name' => 'test.csv',
        ]);

        // Assert the ProcessCsv job was dispatched
        Queue::assertPushed(ProcessCsv::class); 
    }
}
