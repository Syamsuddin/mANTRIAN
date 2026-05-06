<?php

namespace App\Services;

use App\Models\OperatingHour;
use Carbon\CarbonImmutable;

class OperatingHoursService
{
    public function isOpen(?CarbonImmutable $now = null): bool
    {
        $now ??= CarbonImmutable::now(config('app.timezone'));
        $row = OperatingHour::where('day_of_week', $now->dayOfWeekIso)->first();

        if (! $row) {
            return true;
        }

        if ($row->is_closed) {
            return false;
        }

        if (! $row->open_time || ! $row->close_time) {
            return true;
        }

        return $now->format('H:i:s') >= $row->open_time && $now->format('H:i:s') <= $row->close_time;
    }
}
