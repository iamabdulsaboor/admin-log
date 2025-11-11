<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpiNumber extends Model
{
    use HasFactory;

    protected $fillable = ['npi_number'];
}
