<?php

namespace App\Services;

use App\Models\ContractAddendum;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use Carbon\Carbon;

class LetterNumberService
{
    /**
     * Map month number to Roman numeral.
     *
     * @var array<int, string>
     */
    protected const ROMAN_MONTHS = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    /**
     * Convert integer month (1-12) to Roman numeral.
     */
    public static function romanMonth(int $month): string
    {
        return self::ROMAN_MONTHS[$month] ?? 'I';
    }

    /**
     * Generate Offering Letter Number: {Angka}/{Bulan Romawi}/{Tahun YYYY}/OL
     * Sequence resets to 1 each year.
     */
    public static function generateOfferingLetterNumber(?Carbon $date = null): string
    {
        $date = $date ?? Carbon::today();
        $year = $date->year;
        $romanMonth = self::romanMonth($date->month);

        $count = OfferingLetter::query()
            ->whereYear('offer_date', $year)
            ->count();

        $sequence = $count + 1;

        return sprintf('%03d/%s/%d/OL', $sequence, $romanMonth, $year);
    }

    /**
     * Generate Contract Number: {Angka}/{Bulan Romawi}/{Tahun YYYY}/{PKWT|MT|MAGANG}
     * Sequence for contracts resets to 1 each year.
     */
    public static function generateContractNumber(string $contractType = 'PKWT', ?Carbon $date = null): string
    {
        $date = $date ?? Carbon::today();
        $year = $date->year;
        $romanMonth = self::romanMonth($date->month);
        $code = strtoupper(trim($contractType));

        // Valid codes: PKWT, MT, MAGANG (or custom)
        if (! in_array($code, ['PKWT', 'MT', 'MAGANG'])) {
            $code = 'PKWT';
        }

        $count = EmployeeContract::query()
            ->whereYear('start_date', $year)
            ->count();

        $sequence = $count + 1;

        return sprintf('%03d/%s/%d/%s', $sequence, $romanMonth, $year, $code);
    }

    /**
     * Generate Addendum Number: {Angka}/{Bulan Romawi}/{Tahun YYYY}/A-{KODE}
     * Sequence for addendums is separate and resets to 1 each year.
     */
    public static function generateAddendumNumber(string $contractType = 'PKWT', ?Carbon $date = null): string
    {
        $date = $date ?? Carbon::today();
        $year = $date->year;
        $romanMonth = self::romanMonth($date->month);
        $cleanType = strtoupper(trim($contractType));
        $code = 'A-'.$cleanType;

        $count = ContractAddendum::query()
            ->whereYear('issue_date', $year)
            ->count();

        $sequence = $count + 1;

        return sprintf('%03d/%s/%d/%s', $sequence, $romanMonth, $year, $code);
    }
}
