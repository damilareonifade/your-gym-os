<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasImage extends Model
{
    use HasFactory, HasUlids;

    protected $connection = "transactions";

    protected $guarded = [
        "id"
    ];

    protected function casts(): array
    {
        return [
            "icon" => "array",
            'tag' => 'array',
            "has_image" => "boolean",
            "has_icon" => "boolean"
        ];
    }

    public function getOwnerMorphName()
    {
        return $this->getMorphClass();
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
