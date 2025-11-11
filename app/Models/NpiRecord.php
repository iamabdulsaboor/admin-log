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
    ];
}
