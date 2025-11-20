<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpiNumber;

class NpinumberController extends Controller
{
    // Single method to handle GET (view) and POST (bulk upload)
    public function uploadPage(Request $request)
    {
        // Handle POST form submission
        if ($request->isMethod('post')) {
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

            return redirect()->route('npi.upload')
                ->with('success', count($npiNumbers) . ' NPI numbers imported successfully.');
        }

        // Handle GET request: show paginated records
        $records = NpiNumber::latest()->paginate(50);
        return view('npi.upload', compact('records'));
    }

    // Mark NPI as used via AJAX
    public function markUsed(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:npi_numbers,id',
        ]);

        $npi = NpiNumber::find($request->id);
        $npi->used = true; // make sure 'used' column exists in DB
        $npi->save();

        return response()->json(['success' => true]);
    }
}
