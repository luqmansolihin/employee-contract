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
        $activeContractsCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->count();
        $totalAddendums = ContractAddendum::query()->count();

        // 2. Contracts Expiring Soon (<= 30 Days) - Latest active contract per employee
        $expiringContractsQuery = EmployeeContract::query()
            ->with(['employee'])
            ->where('status', 'active')
            ->whereBetween('end_date', [$today, $thirtyDaysAhead])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('employee_contracts')
                    ->groupBy('employee_id');
            });

        $expiringContractsCount = (clone $expiringContractsQuery)->count();

        $expiringContracts = $expiringContractsQuery
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // 3. Contracts Already Expired - Latest contract per employee that has expired
        $expiredContractsQuery = EmployeeContract::query()
            ->with(['employee'])
            ->where(function ($query) use ($today) {
                $query->where('status', 'expired')
                    ->orWhere(function ($q) use ($today) {
                        $q->where('status', '!=', 'renewed')
                            ->where('end_date', '<', $today);
                    });
            })
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('employee_contracts')
                    ->groupBy('employee_id');
            });

        $expiredContractsCount = (clone $expiredContractsQuery)->count();

        $expiredContracts = $expiredContractsQuery
            ->orderBy('end_date', 'desc')
            ->take(5)
            ->get();

        // 4. Contract Type Distribution (Active & Current)
        $pkwtCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->where(function ($query) {
                $query->where('contract_type', 'PKWT')
                    ->orWhereNull('contract_type');
            })
            ->count();

        $mtCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->where('contract_type', 'MT')
            ->count();

        $magangCount = EmployeeContract::query()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->where('contract_type', 'MAGANG')
            ->count();

        // 5. Recent Documents
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
            'expiredContractsCount',
            'expiredContracts',
            'pkwtCount',
            'mtCount',
            'magangCount',
            'recentOfferingLetters',
            'recentContracts'
        ));
    }
}
