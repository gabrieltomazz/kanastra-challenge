<?php

namespace Tests\Feature;

use App\Jobs\ProcessCsv;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCsvTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_process_csv_job()
    {
        // Sample CSV file
        $csvContent = "name,governmentId,email,debtAmount,debtDueDate,debtId\nJohn Doe,123456789,john.doe@example.com,1000.00,2024-07-01,D123456\nJane Smith,987654321,jane.smith@example.com,2000.00,2024-07-01,D654321\n";
        $filePath = 'csv_uploads/sample.csv';
        Storage::put($filePath, $csvContent);

        // File record
        $file = File::create([
            'name' => 'sample.csv',
            'path' => $filePath,
            'status' => FILE::STATUS_PROCESSING,
        ]);

        // Dispatch the job
        ProcessCsv::dispatch($file->id, Storage::path($filePath));

        // Run the job
        $this->artisan('queue:work --stop-when-empty');

        // Assert the File status PROCESSED
        $file->refresh();
        $this->assertEquals('PROCESSED', $file->status);

        // Assert the bankslips have been inserted into the database
        $this->assertDatabaseHas('bankslips', [
            'file_id' => $file->id,
            'name' => 'John Doe',
            'government_id' => '123456789',
            'email' => 'john.doe@example.com',
            'debt_amount' => 1000.00,
            'debt_due_date' => '2024-07-01',
            'debt_id' => 'D123456',
        ]);

        $this->assertDatabaseHas('bankslips', [
            'file_id' => $file->id,
            'name' => 'Jane Smith',
            'government_id' => '987654321',
            'email' => 'jane.smith@example.com',
            'debt_amount' => 2000.00,
            'debt_due_date' => '2024-07-01',
            'debt_id' => 'D654321',
        ]);
    }
}
