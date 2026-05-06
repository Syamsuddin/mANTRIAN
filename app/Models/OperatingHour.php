<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['day_of_week', 'open_time', 'close_time', 'is_closed'])]
class OperatingHour extends Model
{
    protected function casts(): array
    {
        return ['is_closed' => 'boolean'];
    }
}
