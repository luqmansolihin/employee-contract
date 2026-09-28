<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;

class ContractDurationService
{
    /**
     * Calculate inclusive difference between two contract dates.
     * Contracts in HR are inclusive of both start date and end date.
     *
     * @return array{years: int, months: int, days: int, total_days: int}
     */
    public static function diff(
        CarbonInterface|DateTimeInterface|string|null $startDate,
        CarbonInterface|DateTimeInterface|string|null $endDate
    ): array {
        if (! $startDate || ! $endDate) {
            return ['years' => 0, 'months' => 0, 'days' => 0, 'total_days' => 0];
        }

        $start = $startDate instanceof CarbonInterface ? $startDate->copy()->startOfDay() : Carbon::parse($startDate)->startOfDay();
        $end = $endDate instanceof CarbonInterface ? $endDate->copy()->startOfDay() : Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            return ['years' => 0, 'months' => 0, 'days' => 0, 'total_days' => 0];
        }

        // Inclusive end date: adding 1 day so that start to end covers the full final day
        $inclusiveEnd = $end->copy()->addDay();
        $diff = $start->diff($inclusiveEnd);

        $totalDays = (int) $start->diffInDays($inclusiveEnd);

        return [
            'years' => (int) $diff->y,
            'months' => (int) $diff->m,
            'days' => (int) $diff->d,
            'total_days' => $totalDays,
        ];
    }

    /**
     * Format contract period into human-readable Indonesian duration string:
     * e.g. "1 Tahun", "1 Bulan", "1 Tahun 6 Bulan", "1 Tahun 1 Bulan 15 Hari", "15 Hari"
     */
    public static function format(
        CarbonInterface|DateTimeInterface|string|null $startDate,
        CarbonInterface|DateTimeInterface|string|null $endDate
    ): string {
        if (! $startDate || ! $endDate) {
            return '-';
        }

        $diff = self::diff($startDate, $endDate);

        if ($diff['total_days'] <= 0) {
            return '-';
        }

        $parts = [];
        if ($diff['years'] > 0) {
            $parts[] = "{$diff['years']} Tahun";
        }
        if ($diff['months'] > 0) {
            $parts[] = "{$diff['months']} Bulan";
        }
        if ($diff['days'] > 0) {
            $parts[] = "{$diff['days']} Hari";
        }

        return ! empty($parts) ? implode(' ', $parts) : '0 Hari';
    }
}
