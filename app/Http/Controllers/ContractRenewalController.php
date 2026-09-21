<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRenewalRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContractRenewalController extends Controller
{
    /**
     * Show the contract renewal form.
     */
    public function create(Employee $employee): View
    {
        $latestContract = $employee->latestContract;
        $nextSequence = ($employee->contracts()->max('contract_sequence') ?? 0) + 1;

        $suggestedStartDate = $latestContract?->end_date
            ? $latestContract->end_date->copy()->addDay()->toDateString()
            : Carbon::today()->toDateString();

        $suggestedEndDate = Carbon::parse($suggestedStartDate)->addYear()->toDateString();

        return view('employees.renew', compact(
            'employee',
            'latestContract',
            'nextSequence',
            'suggestedStartDate',
            'suggestedEndDate'
        ));
    }

    /**
     * Store a newly created contract extension in storage.
     */
    public function store(StoreContractRenewalRequest $request, Employee $employee): RedirectResponse
    {
        DB::transaction(function () use ($request, $employee) {
            // Mark previously active contracts as renewed
            $employee->contracts()
                ->where('status', 'active')
                ->update(['status' => 'renewed']);

            // Calculate next sequence
            $nextSequence = ($employee->contracts()->max('contract_sequence') ?? 0) + 1;

            // Create new extended contract
            $employee->contracts()->create([
                'contract_sequence' => $nextSequence,
                'contract_number' => $request->contract_number,
                'position' => $request->position,
                'branch' => $request->branch,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            // Update current summary cache on employee record
            $employee->update([
                'current_position' => $request->position,
                'current_branch' => $request->branch,
                'current_contract_end_date' => $request->end_date,
            ]);
        });

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', "Kontrak perpanjangan untuk {$employee->name} berhasil disimpan.");
    }
}
