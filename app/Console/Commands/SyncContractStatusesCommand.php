<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\ContractStatusService;
use Illuminate\Console\Command;

class SyncContractStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:sync-statuses {--employee= : Optional employee ID to synchronize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize contract statuses (expired, renewed, active) and employee cached data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $employeeId = $this->option('employee');
        $targetEmployee = null;

        if ($employeeId) {
            $targetEmployee = Employee::find($employeeId);
            if (! $targetEmployee) {
                $this->error("Employee with ID {$employeeId} not found.");

                return self::FAILURE;
            }
        }

        $this->info('Starting contract statuses synchronization...');

        $results = ContractStatusService::syncStatuses($targetEmployee);

        $this->info('Contract statuses synchronization completed successfully:');
        $this->line("- Expired contracts updated : {$results['expired_count']}");
        $this->line("- Renewed contracts updated : {$results['renewed_count']}");
        $this->line("- Employees data synced     : {$results['employees_synced']}");

        return self::SUCCESS;
    }
}
