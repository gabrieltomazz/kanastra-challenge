<?php

namespace App\Jobs;

use App\Models\Bankslip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class BuildBankslipPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileId;
    /**
     * Job instance.
     */
    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $bankslips = Bankslip::where('file_id', $this->fileId)->get();
            foreach($bankslips as $bankslip)
            {
                Pdf::loadView('pdf.bankslip', ['bankslip' => $bankslip])->save('public/bankslips/' . $bankslip->debt_id . '.pdf');
                SendEmailJob::dispatch($bankslip);
            }

        } catch(Exception $e)
        {
            Log::error('Error to create bank slip pdf: ' . $e->getMessage());
        }
    }
}
