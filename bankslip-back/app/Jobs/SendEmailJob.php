<?php

namespace App\Jobs;

use App\Mail\BankslipProcessed;
use App\Models\Bankslip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;



class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;    

    public $bankslip;
    /**
     * Job instance.
     */
    public function __construct(Bankslip $bankslip)
    {
        $this->bankslip = $bankslip;
    }

    /**
     * Send email
     */
    public function handle(): void
    {
        try {
            Mail::to($this->bankslip->email)->send(new BankslipProcessed($this->bankslip));
            Log::info('Email sent successfully' . $this->bankslip);
        } catch(Exception $e)
        {
            Log::error('Error processing CSV: ' . $e->getMessage());
        }
    }
}
