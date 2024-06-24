<?php

namespace App\Jobs;

use App\Models\Bankslip;
use App\Models\File;
use League\Csv\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;


class ProcessCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileId;
    protected $filePath;

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
            
            $csv->each(function ($record) {
                $this->processRow($record);
            }, $chunkSize);

            // Update file status to PROCESSED
            $file = File::find($this->fileId);
            $file->status = File::STATUS_PROCESSED;
            $file->save();

            Log::info('CSV processed successfully: ' . $this->filePath);
        }catch(Exception $e)
        {
            Log::error('Error processing CSV: ' . $e->getMessage());
        }
    }

    private function processRow(array $record,)
    {
        Bankslip::create([
            'name' => $record['name'],
            'government_id' => $record['governmentId'],
            'email' => $record['email'],
            'debt_amount' => $record['debtAmount'],
            'debt_due_date' => $record['debtDueDate'],
            'debt_id' => $record['debtId'],
            'file_id' => $this->fileId
        ]);
    }

}
