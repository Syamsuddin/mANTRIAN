<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'description', 'prefix', 'color', 'is_active', 'sort_order'])]
class Service extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function counters()
    {
        return $this->belongsToMany(Counter::class, 'counter_services')->withTimestamps();
    }
}
