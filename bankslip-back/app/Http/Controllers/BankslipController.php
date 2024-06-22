<?php

namespace App\Http\Controllers;

use App\Models\Bankslip;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Illuminate\Support\Facades\Validator;

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

        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            Bankslip::create([
                'name' => $record['name'],
                'government_id' => $record['governmentId'],
                'email' => $record['email'],
                'debt_amount' => $record['debtAmount'],
                'debt_due_date' => $record['debtDueDate'],
                'debt_id' => $record['debtId'],
            ]);
        }

        return response()->json(['message' => 'CSV processed successfully'], 200);
    }
}
