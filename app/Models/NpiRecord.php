<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'npi_number',
        'organization_name',
        'authorized_official',
        'official_title',
        'telephone',
        'fax',
        'address',
        'city',
        'state',
        'postal_code',
        'taxonomy_desc',

        // --- Tracking Fields ---
        'is_called',        // boolean: has the lead been called at least once
        'call_count',       // number of calls made
        'last_called_at',   // datetime of the last call
        'called_by',        // agent or user name
        'call_notes',       // notes from the call
    ];
}
