<?php

namespace Tests\Unit;

use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use App\Services\ContractDurationService;
use Carbon\Carbon;
use Tests\TestCase;

class ContractDurationServiceTest extends TestCase
{
    public function test_formats_exact_one_month_correctly(): void
    {
        // Case: 01 Aug 2026 to 31 Aug 2026 (August has 31 days)
        $duration = ContractDurationService::format('2026-08-01', '2026-08-31');
        $this->assertEquals('1 Bulan', $duration);

        // Case: 01 Sep 2026 to 30 Sep 2026 (September has 30 days)
        $duration = ContractDurationService::format('2026-09-01', '2026-09-30');
        $this->assertEquals('1 Bulan', $duration);
    }

    public function test_formats_february_durations_correctly(): void
    {
        // Non-leap year Feb: 28 days
        $duration = ContractDurationService::format('2026-02-01', '2026-02-28');
        $this->assertEquals('1 Bulan', $duration);

        // Leap year Feb: 29 days
        $duration = ContractDurationService::format('2024-02-01', '2024-02-29');
        $this->assertEquals('1 Bulan', $duration);
    }

    public function test_formats_exact_one_year_correctly(): void
    {
        // 01 Aug 2026 to 31 Jul 2027
        $duration = ContractDurationService::format('2026-08-01', '2027-07-31');
        $this->assertEquals('1 Tahun', $duration);

        // 01 Oct 2026 to 30 Sep 2027
        $duration = ContractDurationService::format('2026-10-01', '2027-09-30');
        $this->assertEquals('1 Tahun', $duration);

        // 15 Jan 2026 to 14 Jan 2027
        $duration = ContractDurationService::format('2026-01-15', '2027-01-14');
        $this->assertEquals('1 Tahun', $duration);
    }

    public function test_formats_combination_of_years_months_and_days(): void
    {
        // 1 Year 1 Month 15 Days
        $duration = ContractDurationService::format('2026-08-01', '2027-09-15');
        $this->assertEquals('1 Tahun 1 Bulan 15 Hari', $duration);

        // 1 Month 15 Days
        $duration = ContractDurationService::format('2026-01-01', '2026-02-15');
        $this->assertEquals('1 Bulan 15 Hari', $duration);

        // 15 Days
        $duration = ContractDurationService::format('2026-08-01', '2026-08-15');
        $this->assertEquals('15 Hari', $duration);

        // 1 Day (single day contract)
        $duration = ContractDurationService::format('2026-08-01', '2026-08-01');
        $this->assertEquals('1 Hari', $duration);
    }

    public function test_returns_dash_for_invalid_or_null_dates(): void
    {
        $this->assertEquals('-', ContractDurationService::format(null, '2026-08-31'));
        $this->assertEquals('-', ContractDurationService::format('2026-08-01', null));
        $this->assertEquals('-', ContractDurationService::format('2026-08-31', '2026-08-01'));
    }

    public function test_offering_letter_accessor(): void
    {
        $ol = new OfferingLetter([
            'proposed_start_date' => '2026-08-01',
            'proposed_end_date' => '2026-08-31',
        ]);

        $this->assertEquals('1 Bulan', $ol->formatted_duration);
    }

    public function test_employee_contract_accessor(): void
    {
        $contract = new EmployeeContract([
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
        ]);

        $this->assertEquals('1 Tahun', $contract->formatted_duration);
    }
}
