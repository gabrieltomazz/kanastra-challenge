<?php

namespace App\Jobs;

use App\Models\Bankslip;
use App\Models\File;
use App\Jobs\SendEmailJob;
use League\Csv\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;


class ProcessCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileId;
    protected $filePath;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;    

    /**
     * Create a new job instance.
     */
    public function __construct($fileId, $filePath)
    {
        $this->fileId = $fileId;
        $this->filePath = $filePath;
    }

    /**
     * Process csv job.
     */
    public function handle(): void
    {
        try {
            $csv = Reader::createFromPath($this->filePath, 'r'); // read csv
            $csv->setHeaderOffset(0); // skip header 
            
            $chunkSize = 1000; // Number of rows per chunk
            $bankSlipData = [];

            // process csv
            $csv->each(function ($record)use (&$bankSlipData, &$chunkSize) {
                $bankSlipData[] = [
                    'file_id' => $this->fileId,
                    'name' => $record['name'],
                    'government_id' => $record['governmentId'],
                    'email' => $record['email'],
                    'debt_amount' => $record['debtAmount'],
                    'debt_due_date' => $record['debtDueDate'],
                    'debt_id' => $record['debtId'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
               
                // Insert bankslip in batches
                if (count($bankSlipData) >= $chunkSize) {
                    DB::table('bankslips')->insert($bankSlipData);
                    $bankSlipData = []; // Reset the array
                }
            }, $chunkSize);

            // Insert remaining debts
            if (!empty($bankSlipData)) {
                DB::table('bankslips')->insert($bankSlipData);
            }

            // Update file status to PROCESSED
            $this->updateFile(File::STATUS_PROCESSED);

            // call job to create pdf
            BuildBankslipPDF::dispatch($this->fileId);

            Log::info('CSV processed successfully: ' . $this->filePath);
        }catch(Exception $e)
        {
            $this->updateFile(File::STATUS_ERROR);
            Log::error('Error processing CSV: ' . $e->getMessage());
        }
    }

    private function updateFile($status)
    {
        // Update file status
        $file = File::find($this->fileId);
        $file->status = $status;
        $file->save();
    }
}
