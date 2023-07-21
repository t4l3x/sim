<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attribute extends Model
{
    use HasFactory;
    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }
}
