<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource with filters and summary stats.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today()->toDateString();
        $thirtyDaysAhead = Carbon::today()->addDays(30)->toDateString();

        // Summary KPI stats
        $totalCount = Employee::query()->count();
        $activeCount = Employee::query()->where('current_contract_end_date', '>', $thirtyDaysAhead)->count();
        $expiringSoonCount = Employee::query()->whereBetween('current_contract_end_date', [$today, $thirtyDaysAhead])->count();
        $expiredCount = Employee::query()->where('current_contract_end_date', '<', $today)->count();

        // Unique filter options
        $branches = Employee::query()->distinct()->whereNotNull('current_branch')->orderBy('current_branch')->pluck('current_branch');
        $positions = Employee::query()->distinct()->whereNotNull('current_position')->orderBy('current_position')->pluck('current_position');

        // Query employees with search & filters
        $employees = Employee::query()
            ->with(['contracts'])
            ->search($request->input('search'))
            ->filterStatus($request->input('status'))
            ->filterBranch($request->input('branch'))
            ->filterPosition($request->input('position'))
            ->orderBy('current_contract_end_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'totalCount',
            'activeCount',
            'expiringSoonCount',
            'expiredCount',
            'branches',
            'positions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage (with initial contract).
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = DB::transaction(function () use ($request) {
            $employee = Employee::create([
                'name' => $request->name,
                'ktp_number' => $request->ktp_number,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'first_join_date' => $request->join_date,
                'current_position' => $request->position,
                'current_branch' => $request->branch,
                'current_contract_end_date' => $request->contract_end_date,
            ]);

            $employee->contracts()->create([
                'contract_sequence' => 1,
                'contract_number' => $request->contract_number,
                'position' => $request->position,
                'branch' => $request->branch,
                'start_date' => $request->join_date,
                'end_date' => $request->contract_end_date,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            return $employee;
        });

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', "Data karyawan {$employee->name} dan kontrak awal berhasil ditambahkan.");
    }

    /**
     * Display the specified resource with full contract history.
     */
    public function show(Employee $employee): View
    {
        $employee->load(['contracts']);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', "Biodata karyawan {$employee->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        Gate::authorize('delete', $employee);

        $name = $employee->name;
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', "Data karyawan {$name} beserta seluruh riwayat kontrak berhasil dihapus.");
    }
}
