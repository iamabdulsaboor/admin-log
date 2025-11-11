<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpiNumber;

class NpinumberController extends Controller
{
    // Show Upload Page
    public function uploadPage()
    {
        $records = NpiNumber::latest()->get();
        return view('npi.upload', compact('records'));
    }

    // Bulk Upload Logic
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'npi_bulk' => 'nullable|string',
            'file' => 'nullable|file|mimes:csv,txt',
        ]);

        $npiNumbers = [];

        if ($request->hasFile('file')) {
            $lines = file($request->file('file')->getRealPath());
            foreach ($lines as $line) {
                $npi = trim($line);
                if ($npi !== '') {
                    $npiNumbers[] = $npi;
                }
            }
        } elseif (!empty($request->npi_bulk)) {
            $npiNumbers = preg_split('/[\s,]+/', trim($request->npi_bulk));
        }

        $npiNumbers = array_unique(array_filter($npiNumbers));

        foreach ($npiNumbers as $npi) {
            NpiNumber::updateOrCreate(['npi_number' => $npi]);
        }

        return back()->with('success', count($npiNumbers) . ' NPI numbers imported successfully.');
    }
}
