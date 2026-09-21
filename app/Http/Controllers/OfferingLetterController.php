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
     * Show the form for creating a new offering letter for a specific employee.
     */
    public function create(Employee $employee): View
    {
        $suggestedNumber = LetterNumberService::generateOfferingLetterNumber(Carbon::today());

        return view('offering_letters.create', compact('employee', 'suggestedNumber'));
    }

    /**
     * Store a newly created offering letter in storage.
     */
    public function store(StoreOfferingLetterRequest $request, Employee $employee): RedirectResponse
    {
        $offeringLetter = $employee->offeringLetters()->create([
            'letter_number' => $request->letter_number,
            'offer_date' => $request->offer_date,
            'contract_type' => $request->contract_type,
            'position' => $request->position,
            'branch' => $request->branch,
            'proposed_start_date' => $request->proposed_start_date,
            'proposed_end_date' => $request->proposed_end_date,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance ?? 0,
            'valid_until' => $request->valid_until,
            'status' => 'draft',
            'terms' => $request->terms,
            'notes' => $request->notes,
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
