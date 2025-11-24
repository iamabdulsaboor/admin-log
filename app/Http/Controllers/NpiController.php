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
            ->paginate(10);

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

                // -------------------------
                // NEW Tracking Fields
                // -------------------------
                'is_called' => false,        // default: never called
                'call_count' => 0,           // default: 0 calls made
                'last_called_at' => null,    // no call time yet
                'called_by' => null,         // no agent yet
                'call_notes' => null,        // no notes yet
            ]
        );

        return back()->with('success', 'NPI data saved successfully!');
    }
    public function updateCall(Request $request, $id)
{
    $rec = NpiRecord::findOrFail($id);

    $rec->is_called = true;
    $rec->call_count = $rec->call_count + 1;
    $rec->last_called_at = now();
    $rec->called_by = auth()->check() ? auth()->user()->name : "Agent";
    $rec->call_notes = $request->call_notes;
    $rec->save();

    return response()->json([
        "status" => "success",
        "call_count" => $rec->call_count,
        "last_called_at" => $rec->last_called_at->toDateTimeString()
    ]);
}

}
