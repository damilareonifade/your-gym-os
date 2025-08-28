<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationMenu extends Model
{
    use HasUlids, SoftDeletes;

    protected $guarded = ["id"];

    public function model()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'landing_menu_id', 'id');
    }

    public function subMenu()
    {
        return $this->hasMany(NavigationMenu::class, 'landing_menu_id');
    }

    public function images()
    {
        return $this->morphMany(ModelHasImage::class, 'imageable');
    }
}