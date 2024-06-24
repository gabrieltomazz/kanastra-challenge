<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Jobs\ProcessCsv;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BankslipController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $file = $request->file('file');
            $filePath = $file->store('csv_uploads');
    
            $savedFile = File::create(['name' => $file->getClientOriginalName()]);
            ProcessCsv::dispatch($savedFile->id, Storage::path($filePath));

            return response()->json(['message' => 'CSV processed successfully'], 200);

        }catch(Exception $e)
        {
            Log::error('Error processing CSV: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed. Please try again.'], 500);
        }

    }
}
