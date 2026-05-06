<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service_id', 'ticket_date', 'sequence_no', 'ticket_no', 'service_name_snapshot', 'status', 'printed_at', 'called_at', 'started_at', 'completed_at', 'skipped_at', 'cancelled_at', 'cancel_reason', 'meta'])]
class Ticket extends Model
{
    use HasFactory;

    public const ACTIVE_STATUSES = ['called', 'serving'];

    protected function casts(): array
    {
        return [
            'ticket_date' => 'date',
            'printed_at' => 'datetime',
            'called_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'skipped_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function calls()
    {
        return $this->hasMany(QueueCall::class);
    }
}
