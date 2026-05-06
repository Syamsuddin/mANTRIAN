<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_id', 'counter_id', 'operator_id', 'call_no', 'event_type', 'counter_name_snapshot', 'operator_name_snapshot', 'called_at', 'notes'])]
class QueueCall extends Model
{
    protected function casts(): array
    {
        return ['called_at' => 'datetime'];
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
