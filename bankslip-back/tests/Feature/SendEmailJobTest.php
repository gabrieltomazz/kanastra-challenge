<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Mail\BankslipProcessed;
use App\Models\Bankslip;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake(); // Fake the mail
    }
    public function test_send_email_job_sends_email()
    {
         // File record
         $file = File::create([
            'name' => 'sample.csv',
            'status' => FILE::STATUS_PROCESSING,
        ]);

        // Create a sample Bankslip record
        $bankslip = Bankslip::create([
            'file_id' => $file->id, 
            'name' => 'John Doe',
            'government_id' => '123456789',
            'email' => 'john.doe@example.com',
            'debt_amount' => 1000.00,
            'debt_due_date' => '2024-07-01',
            'debt_id' => 'D123456',
        ]);

        // Dispatch the job
        SendEmailJob::dispatch($bankslip);

        // Run the job
        $this->artisan('queue:work --stop-when-empty');

        // Assert the email was sent
        Mail::assertSent(BankslipProcessed::class, function ($mail) use ($bankslip) {
            return $mail->hasTo($bankslip->email) && $mail->bankslip->is($bankslip);
        });
    }
}
