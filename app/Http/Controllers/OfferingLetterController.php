<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferingLetterRequest;
use App\Http\Requests\UpdateOfferingLetterRequest;
use App\Models\Employee;
use App\Models\OfferingLetter;
use App\Services\LetterNumberService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferingLetterController extends Controller
{
    /**
     * Display a listing of offering letters.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = OfferingLetter::query()
            ->with(['employee'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('letter_number', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('branch', 'like', "%{$search}%")
                    ->orWhere('bidang', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn ($eq) => $eq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('offer_date', 'desc');

        $offeringLetters = $query->paginate(10)->withQueryString();

        $counts = [
            'total' => OfferingLetter::query()->count(),
            'draft' => OfferingLetter::query()->where('status', 'draft')->count(),
            'sent' => OfferingLetter::query()->where('status', 'sent')->count(),
            'accepted' => OfferingLetter::query()->where('status', 'accepted')->count(),
            'rejected' => OfferingLetter::query()->where('status', 'rejected')->count(),
        ];

        return view('offering_letters.index', compact('offeringLetters', 'counts'));
    }

    /**
     * Show the form for creating a new offering letter.
     */
    public function create(Request $request, ?Employee $employee = null): View
    {
        if (! $employee && $request->filled('employee_id')) {
            $employee = Employee::find($request->employee_id);
        }

        $suggestedNumber = LetterNumberService::generateOfferingLetterNumber(Carbon::today());
        $employees = Employee::orderBy('name')->get(['id', 'name', 'ktp_number', 'current_position', 'current_branch', 'first_join_date']);
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
        ]);

        return view('offering_letters.create', compact('employee', 'employees', 'suggestedNumber'));
    }

    /**
     * Store a newly created offering letter in storage.
     */
    public function store(StoreOfferingLetterRequest $request, ?Employee $employee = null): RedirectResponse
    {
        $employeeId = $employee?->id ?? $request->employee_id;
        $employee = Employee::findOrFail($employeeId);

        $offeringLetter = $employee->offeringLetters()->create([
            'letter_number' => $request->letter_number,
            'kode' => $request->kode,
            'offer_date' => $request->offer_date,
            'contract_type' => $request->contract_type ?? 'PKWT',
            'position' => $request->position,
            'bidang' => $request->bidang,
            'branch' => $request->branch,
            'proposed_start_date' => $request->proposed_start_date,
            'proposed_end_date' => $request->proposed_end_date,
            'basic_salary' => $request->basic_salary ?? 0,
            'allowance' => $request->allowance ?? 0,
            'valid_until' => $request->valid_until,
            'status' => 'draft',
            'terms' => $request->terms,
            'notes' => $request->notes,
            'supervisor_name' => $request->supervisor_name,
            'supervisor_position' => $request->supervisor_position,
            'office_address' => $request->office_address,
        ]);

        return redirect()
            ->route('offering-letters.show', $offeringLetter)
            ->with('success', "Surat Penawaran ({$offeringLetter->letter_number}) untuk {$employee->name} berhasil dibuat.");
    }

    /**
     * Display the specified offering letter.
     */
    public function show(OfferingLetter $offeringLetter): View
    {
        $offeringLetter->load(['employee', 'contract']);

        return view('offering_letters.show', compact('offeringLetter'));
    }

    /**
     * Show the form for editing the specified offering letter.
     */
    public function edit(OfferingLetter $offeringLetter): View
    {
        $offeringLetter->load('employee');

        return view('offering_letters.edit', compact('offeringLetter'));
    }

    /**
     * Update the specified offering letter in storage.
     */
    public function update(UpdateOfferingLetterRequest $request, OfferingLetter $offeringLetter): RedirectResponse
    {
        $offeringLetter->update($request->validated());

        return redirect()
            ->route('offering-letters.show', $offeringLetter)
            ->with('success', "Surat Penawaran ({$offeringLetter->letter_number}) berhasil diperbarui.");
    }

    /**
     * Update status of the offering letter.
     */
    public function updateStatus(Request $request, OfferingLetter $offeringLetter): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:draft,sent,accepted,rejected'],
        ]);

        $offeringLetter->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with('success', "Status Surat Penawaran berhasil diubah menjadi {$offeringLetter->status_label}.");
    }

    /**
     * Printable view of the offering letter.
     */
    public function print(OfferingLetter $offeringLetter): View
    {
        $offeringLetter->load('employee');

        return view('offering_letters.print', compact('offeringLetter'));
    }
}
