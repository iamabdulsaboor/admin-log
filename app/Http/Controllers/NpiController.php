<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpiRecord;
use Illuminate\Support\Facades\Http;

class NpiController extends Controller
{
    public function index()
{
    // 10 records per page, ordered latest first
    $records = NpiRecord::latest()->paginate(10);
    return view('npi.index', compact('records'));
}


public function search(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return back()->with('error', 'Please enter a search value.');
    }

    $results = NpiRecord::where('npi_number', 'LIKE', "%{$query}%")
        ->orWhere('organization_name', 'LIKE', "%{$query}%")
        ->orWhere('authorized_official', 'LIKE', "%{$query}%")
        ->orWhere('taxonomy_desc', 'LIKE', "%{$query}%")
        ->paginate(10); // Use paginate here too

    return view('npi.index', compact('results'));
}


    public function store(Request $request)
    {
        $request->validate(['npi_number' => 'required|numeric']);

        $response = Http::get("https://npiregistry.cms.hhs.gov/api/", [
            'number' => $request->npi_number,
            'version' => '2.1'
        ]);

        if ($response->failed() || empty($response['results'])) {
            return back()->with('error', 'No record found for this NPI number.');
        }

        $data = $response['results'][0];
        $basic = $data['basic'] ?? [];
        $addr = $data['addresses'][0] ?? [];
        $taxonomy = $data['taxonomies'][0] ?? [];

        NpiRecord::updateOrCreate(
            ['npi_number' => $data['number']],
            [
                'organization_name' => $basic['organization_name'] ?? null,
                'authorized_official' => ($basic['authorized_official_first_name'] ?? '') . ' ' . ($basic['authorized_official_last_name'] ?? ''),
                'official_title' => $basic['authorized_official_title_or_position'] ?? null,
                'telephone' => $addr['telephone_number'] ?? null,
                'fax' => $addr['fax_number'] ?? null,
                'address' => $addr['address_1'] ?? null,
                'city' => $addr['city'] ?? null,
                'state' => $addr['state'] ?? null,
                'postal_code' => $addr['postal_code'] ?? null,
                'taxonomy_desc' => $taxonomy['desc'] ?? null,
            ]
        );

        return back()->with('success', 'NPI data saved successfully!');
    }
}

