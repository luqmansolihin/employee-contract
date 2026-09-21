<?php

namespace App\Http\Controllers;

use App\Models\ContractAddendum;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the executive HR dashboard.
     */
    public function index(): View
    {
        $today = Carbon::today()->toDateString();
        $thirtyDaysAhead = Carbon::today()->addDays(30)->toDateString();

        // 1. Core KPIs
        $totalEmployees = Employee::query()->count();
        $totalOfferingLetters = OfferingLetter::query()->count();
        $pendingOfferingLetters = OfferingLetter::query()->whereIn('status', ['draft', 'sent'])->count();
        $activeContractsCount = EmployeeContract::query()->where('status', 'active')->count();
        $totalAddendums = ContractAddendum::query()->count();

        // 2. Contracts Expiring Soon (<= 30 Days)
        $expiringContractsCount = EmployeeContract::query()
            ->where('status', 'active')
            ->whereBetween('end_date', [$today, $thirtyDaysAhead])
            ->count();

        $expiringContracts = EmployeeContract::query()
            ->with(['employee'])
            ->where('status', 'active')
            ->whereBetween('end_date', [$today, $thirtyDaysAhead])
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // 3. Contract Type Distribution
        $pkwtCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('contract_type', 'PKWT')
                    ->orWhereNull('contract_type');
            })
            ->count();

        $mtCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('contract_type', 'MT')
            ->count();

        $magangCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('contract_type', 'MAGANG')
            ->count();

        // 4. Recent Documents
        $recentOfferingLetters = OfferingLetter::query()
            ->with('employee')
            ->latest('id')
            ->take(5)
            ->get();

        $recentContracts = EmployeeContract::query()
            ->with('employee')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEmployees',
            'totalOfferingLetters',
            'pendingOfferingLetters',
            'activeContractsCount',
            'totalAddendums',
            'expiringContractsCount',
            'expiringContracts',
            'pkwtCount',
            'mtCount',
            'magangCount',
            'recentOfferingLetters',
            'recentContracts'
        ));
    }
}
