<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * ---------------
     * Default country
     * ---------------
     * @var string
     */
    const COUNTRY_BR = "BR";

    protected $fillable = ["address_type", "public_place", "number", "zip_code", "district", "city", "state", "at_id", "at_type"];

}
