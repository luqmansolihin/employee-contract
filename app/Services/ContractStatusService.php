<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractStatusService
{
    /**
     * Synchronize contract statuses and employee cached fields.
     *
     * Rules:
     * 1. Any contract with end_date < today that is still 'active' must be marked as 'expired'
     *    (or 'renewed' if it was superseded by a newer contract).
     * 2. Any contract superseded by a newer contract must be marked as 'renewed' (never left as 'active').
     * 3. Only the employee's latest ongoing contract (end_date >= today) should be 'active'.
     * 4. Employee cached columns (current_contract_end_date, current_position, current_branch)
     *    are synchronized to the latest contract.
     *
     * @return array{expired_count: int, renewed_count: int, employees_synced: int}
     */
    public static function syncStatuses(?Employee $targetEmployee = null): array
    {
        return DB::transaction(function () use ($targetEmployee) {
            $today = Carbon::today()->startOfDay();
            $expiredCount = 0;
            $renewedCount = 0;
            $employeesSynced = 0;

            $employeeQuery = Employee::query();
            if ($targetEmployee) {
                $employeeQuery->where('id', $targetEmployee->id);
            }

            $employees = $employeeQuery->with(['contracts' => function ($q) {
                $q->orderBy('contract_sequence', 'desc')->orderBy('id', 'desc');
            }])->get();

            foreach ($employees as $employee) {
                $contracts = $employee->contracts;

                if ($contracts->isEmpty()) {
                    if ($employee->current_contract_end_date !== null) {
                        $employee->update(['current_contract_end_date' => null]);
                        $employeesSynced++;
                    }

                    continue;
                }

                $latestContract = $contracts->first();

                // 1. All older contracts (superseded by a newer contract) must NOT be 'active'.
                // If an older contract is still 'active', mark it as 'renewed'.
                foreach ($contracts->slice(1) as $oldContract) {
                    if ($oldContract->status === 'active') {
                        $oldContract->update(['status' => 'renewed']);
                        $renewedCount++;
                    }
                }

                // 2. For the latest contract:
                if ($latestContract->end_date) {
                    $latestEndDate = $latestContract->end_date->copy()->startOfDay();

                    if ($latestEndDate->lt($today)) {
                        // Past contract: must be 'expired' if it was 'active'
                        if ($latestContract->status === 'active') {
                            $latestContract->update(['status' => 'expired']);
                            $expiredCount++;
                        }
                    } else {
                        // Ongoing contract: if it was marked 'expired', revert to 'active'
                        if ($latestContract->status === 'expired') {
                            $latestContract->update(['status' => 'active']);
                        }
                    }
                }

                // 3. Sync employee cached fields with the latest contract
                $needsUpdate = false;
                $updateData = [];

                if ($employee->current_contract_end_date?->toDateString() !== $latestContract->end_date?->toDateString()) {
                    $updateData['current_contract_end_date'] = $latestContract->end_date;
                    $needsUpdate = true;
                }

                if ($latestContract->position && $employee->current_position !== $latestContract->position) {
                    $updateData['current_position'] = $latestContract->position;
                    $needsUpdate = true;
                }

                if ($latestContract->branch && $employee->current_branch !== $latestContract->branch) {
                    $updateData['current_branch'] = $latestContract->branch;
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $employee->update($updateData);
                    $employeesSynced++;
                }
            }

            // Also catch any orphan contracts with end_date < today that might not belong to loaded employees
            if (! $targetEmployee) {
                $orphanExpired = EmployeeContract::query()
                    ->where('status', 'active')
                    ->where('end_date', '<', $today->toDateString())
                    ->update(['status' => 'expired']);

                $expiredCount += $orphanExpired;
            }

            return [
                'expired_count' => $expiredCount,
                'renewed_count' => $renewedCount,
                'employees_synced' => $employeesSynced,
            ];
        });
    }

    /**
     * Synchronize a single employee's contracts and cached data.
     */
    public static function syncEmployee(Employee $employee): void
    {
        self::syncStatuses($employee);
    }
}
