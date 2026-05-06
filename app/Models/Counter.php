<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'location', 'is_active', 'sort_order'])]
class Counter extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'counter_services')->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(CounterAssignment::class);
    }
}
