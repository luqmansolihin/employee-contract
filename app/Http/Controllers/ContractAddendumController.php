<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractAddendumRequest;
use App\Models\ContractAddendum;
use App\Models\EmployeeContract;
use App\Services\LetterNumberService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContractAddendumController extends Controller
{
    /**
     * Display a listing of all contract addendums.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = ContractAddendum::query()
            ->with(['employee', 'contract'])
            ->when($search, function ($q) use ($search) {
                $q->where('addendum_number', 'like', "%{$search}%")
                    ->orWhere('amendment_reason', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn($eq) => $eq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('contract', fn($cq) => $cq->where('contract_number', 'like', "%{$search}%"));
            })
            ->orderBy('issue_date', 'desc');

        $addendums = $query->paginate(10)->withQueryString();

        $totalCount = ContractAddendum::query()->count();

        return view('addendums.index', compact('addendums', 'totalCount'));
    }

    /**
     * Show the form for creating a new addendum for a specific contract.
     */
    public function create(EmployeeContract $contract): View
    {
        $contract->load('employee');
        $nextSequence = $contract->addendums()->count() + 1;
        $suggestedNumber = LetterNumberService::generateAddendumNumber($contract->contract_type, Carbon::today());

        $defaultEffectiveDate = $contract->end_date ? $contract->end_date->copy()->addDay() : Carbon::today();
        $defaultNewEndDate = $defaultEffectiveDate->copy()->addYear()->subDay();

        return view('addendums.create', compact(
            'contract',
            'nextSequence',
            'suggestedNumber',
            'defaultEffectiveDate',
            'defaultNewEndDate'
        ));
    }

    /**
     * Store a newly created addendum in storage.
     */
    public function store(StoreContractAddendumRequest $request, EmployeeContract $contract): RedirectResponse
    {
        $addendum = DB::transaction(function () use ($request, $contract) {
            $nextSequence = $contract->addendums()->count() + 1;
            $employee = $contract->employee;

            $addendum = ContractAddendum::create([
                'employee_id' => $contract->employee_id,
                'employee_contract_id' => $contract->id,
                'addendum_number' => $request->addendum_number,
                'addendum_sequence' => $nextSequence,
                'issue_date' => $request->issue_date,
                'effective_date' => $request->effective_date,
                'previous_end_date' => $contract->end_date,
                'new_end_date' => $request->new_end_date,
                'previous_position' => $contract->position,
                'new_position' => $request->new_position ?: $contract->position,
                'previous_salary' => $contract->basic_salary,
                'new_salary' => $request->new_salary ?: $contract->basic_salary,
                'amendment_reason' => $request->amendment_reason,
                'clause_changes' => $request->clause_changes,
                'status' => 'active',
            ]);

            // Update parent contract with extended end date and updated position/salary if modified
            $contract->update([
                'end_date' => $request->new_end_date,
                'position' => $request->new_position ?: $contract->position,
                'basic_salary' => $request->new_salary ?: $contract->basic_salary,
                'status' => 'active',
            ]);

            // Sync employee cached contract info
            $employee->update([
                'current_contract_end_date' => $request->new_end_date,
                'current_position' => $request->new_position ?: $employee->current_position,
            ]);

            return $addendum;
        });

        return redirect()
            ->route('contracts.show', $contract)
            ->with('success', "Adendum Kontrak ({$addendum->addendum_number}) berhasil diterbitkan dan masa berlaku kontrak diperpanjang hingga {$addendum->new_end_date->format('d/m/Y')}.");
    }

    /**
     * Display the specified contract addendum.
     */
    public function show(ContractAddendum $addendum): View
    {
        $addendum->load(['employee', 'contract']);

        return view('addendums.show', compact('addendum'));
    }

    /**
     * Printable view of the contract addendum.
     */
    public function print(ContractAddendum $addendum): View
    {
        $addendum->load(['employee', 'contract']);

        return view('addendums.print', compact('addendum'));
    }
}
