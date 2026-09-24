<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use App\Services\LetterNumberService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts with type and status filtering.
     */
    public function index(Request $request): View
    {
        $contractType = $request->input('type');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = EmployeeContract::query()
            ->with(['employee', 'offeringLetter', 'addendums'])
            ->when($contractType, fn ($q) => $q->where('contract_type', $contractType))
            ->when($status, function ($q) use ($status) {
                $today = Carbon::today()->toDateString();
                $thirtyDaysAhead = Carbon::today()->addDays(30)->toDateString();

                if ($status === 'active') {
                    $q->where('status', 'active')->where('end_date', '>', $thirtyDaysAhead);
                } elseif ($status === 'expiring_soon') {
                    $q->where('status', 'active')->whereBetween('end_date', [$today, $thirtyDaysAhead]);
                } elseif ($status === 'expired') {
                    $q->where(fn ($sub) => $sub->where('status', 'expired')->orWhere('end_date', '<', $today));
                } elseif ($status === 'renewed') {
                    $q->where('status', 'renewed');
                }
            })
            ->when($search, function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('branch', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn ($eq) => $eq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('end_date', 'asc');

        $contracts = $query->paginate(10)->withQueryString();

        $counts = [
            'total' => EmployeeContract::query()->count(),
            'pkwt' => EmployeeContract::query()->where('contract_type', 'PKWT')->count(),
            'mt' => EmployeeContract::query()->where('contract_type', 'MT')->count(),
            'magang' => EmployeeContract::query()->where('contract_type', 'MAGANG')->count(),
        ];

        return view('contracts.index', compact('contracts', 'counts'));
    }

    /**
     * Show the form for creating a new contract.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $employee = null;
        $offeringLetter = null;
        $selectedType = $request->input('type', 'PKWT');

        if ($request->filled('offering_letter_id')) {
            $offeringLetter = OfferingLetter::with('employee')->findOrFail($request->input('offering_letter_id'));
            if ($offeringLetter->status !== 'accepted') {
                return redirect()
                    ->route('offering-letters.show', $offeringLetter)
                    ->with('error', 'Kontrak kerja hanya dapat diterbitkan untuk Surat Penawaran yang berstatus Diterima (Accepted).');
            }
            $employee = $offeringLetter->employee;
            $selectedType = $offeringLetter->contract_type;
        } elseif ($request->filled('employee_id')) {
            $employee = Employee::findOrFail($request->input('employee_id'));
        }

        $suggestedNumbers = [
            'PKWT' => LetterNumberService::generateContractNumber('PKWT', Carbon::today()),
            'MT' => LetterNumberService::generateContractNumber('MT', Carbon::today()),
            'MAGANG' => LetterNumberService::generateContractNumber('MAGANG', Carbon::today()),
        ];

        $suggestedNumber = $suggestedNumbers[$selectedType] ?? $suggestedNumbers['PKWT'];
        $employees = Employee::orderBy('name')->get(['id', 'name', 'ktp_number', 'current_position', 'current_branch']);
        $employees = Employee::orderBy('name')->get([
            'id',
            'name',
            'ktp_number',
            'gender',
            'birth_place',
            'birth_date',
            'address',
            'current_position',
            'current_branch',
            'first_join_date',
            'current_contract_end_date',
        ]);

        return view('contracts.create', compact('employee', 'offeringLetter', 'suggestedNumber', 'suggestedNumbers', 'selectedType', 'employees'));
    }

    /**
     * Store a newly created contract in storage.
     */
    public function store(StoreContractRequest $request): RedirectResponse
    {
        $contract = DB::transaction(function () use ($request) {
            $employee = Employee::findOrFail($request->employee_id);
            $sequence = $employee->contracts()->count() + 1;

            $offeringLetter = null;
            if ($request->filled('offering_letter_id')) {
                $offeringLetter = OfferingLetter::find($request->offering_letter_id);
            }

            $contract = $employee->contracts()->create([
                'offering_letter_id' => $request->offering_letter_id,
                'contract_sequence' => $sequence,
                'contract_number' => $request->contract_number,
                'contract_type' => $request->contract_type,
                'position' => $request->position,
                'branch' => $request->branch,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'basic_salary' => $request->basic_salary,
                'allowance' => $request->allowance ?? 0,
                'basic_salary' => $request->basic_salary ?? ($offeringLetter?->basic_salary ?? 0),
                'allowance' => $request->allowance ?? ($offeringLetter?->allowance ?? 0),
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            // Update offering letter status to accepted if linked
            if ($request->filled('offering_letter_id')) {
                OfferingLetter::where('id', $request->offering_letter_id)->update(['status' => 'accepted']);
            }

            // Sync employee cached contract info
            $employee->update([
                'first_join_date' => $employee->first_join_date ?? $request->start_date,
                'current_position' => $request->position,
                'current_branch' => $request->branch,
                'current_contract_end_date' => $request->end_date,
            ]);

            return $contract;
        });

        return redirect()
            ->route('contracts.show', $contract)
            ->with('success', "Kontrak kerja ({$contract->contract_number}) untuk {$contract->employee->name} berhasil diterbitkan.");
    }

    /**
     * Display the specified contract.
     */
    public function show(EmployeeContract $contract): View
    {
        $contract->load(['employee', 'offeringLetter', 'addendums']);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Printable view of the contract.
     */
    public function print(EmployeeContract $contract): View
    {
        $contract->load(['employee', 'offeringLetter', 'addendums']);

        return view('contracts.print', compact('contract'));
    }
}
