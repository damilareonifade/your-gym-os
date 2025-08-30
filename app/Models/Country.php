<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasUlids;

    protected $guarded = ["id"];

    protected function casts()
    {
        return [
            "language_code" => "array"
        ];
    }
}