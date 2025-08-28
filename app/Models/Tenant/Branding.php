<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Branding extends Model
{
    use HasUlids;

    protected $fillable = [
        'color',
        'brand_logo',
        'facebook_social_account',
        'instagram_social_account',
        'dark_mode_logo',
        'favicon',
    ];
}
