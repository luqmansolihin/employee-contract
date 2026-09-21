<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeeContract extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'offering_letter_id',
        'contract_sequence',
        'contract_number',
        'contract_type',
        'position',
        'branch',
        'start_date',
        'end_date',
        'basic_salary',
        'allowance',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contract_sequence' => 'integer',
            'basic_salary' => 'decimal:2',
            'allowance' => 'decimal:2',
        ];
    }

    /**
     * Relationship: The employee this contract belongs to.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relationship: Optional offering letter reference.
     */
    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class);
    }

    /**
     * Relationship: Addendums for this contract.
     */
    public function addendums(): HasMany
    {
        return $this->hasMany(ContractAddendum::class, 'employee_contract_id')->orderBy('addendum_sequence', 'asc');
    }

    /**
     * Relationship: Latest addendum for this contract.
     */
    public function latestAddendum(): HasOne
    {
        return $this->hasOne(ContractAddendum::class, 'employee_contract_id')->latestOfMany('addendum_sequence');
    }

    /**
     * Badge CSS class for contract type (PKWT, MT, MAGANG).
     */
    protected function contractTypeBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->contract_type) {
                'PKWT' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'MT' => 'bg-purple-50 text-purple-700 border-purple-200',
                'MAGANG' => 'bg-amber-50 text-amber-700 border-amber-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            }
        );
    }

    /**
     * Formatted basic salary.
     */
    protected function formattedSalary(): Attribute
    {
        return Attribute::make(
            get: fn(): ?string => $this->basic_salary !== null
                ? 'Rp ' . number_format((float) $this->basic_salary, 0, ',', '.')
                : null
        );
    }

    /**
     * Formatted allowance.
     */
    protected function formattedAllowance(): Attribute
    {
        return Attribute::make(
            get: fn(): ?string => $this->allowance !== null
                ? 'Rp ' . number_format((float) $this->allowance, 0, ',', '.')
                : null
        );
    }

    /**
     * Friendly sequence label (e.g. Kontrak #1 (Awal), Kontrak #2 (Perpanjangan 1))
     */
    protected function sequenceLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->contract_sequence === 1) {
                    return 'Kontrak #1 (Awal)';
                }

                $extNumber = $this->contract_sequence - 1;

                return "Kontrak #{$this->contract_sequence} (Perpanjangan {$extNumber})";
            }
        );
    }

    /**
     * Dynamic status attribute taking into account whether it was superseded or expired
     */
    protected function calculatedStatus(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->status === 'renewed') {
                    return 'renewed';
                }

                $today = Carbon::today();
                $endDate = $this->end_date?->copy()->startOfDay();

                if (! $endDate) {
                    return 'unknown';
                }

                if ($endDate->lt($today)) {
                    return 'expired';
                }

                if ($endDate->diffInDays($today) <= 30) {
                    return 'expiring_soon';
                }

                return 'active';
            }
        );
    }

    /**
     * Human-readable label for status
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->calculated_status) {
                    'renewed' => 'Diperpanjang',
                    'active' => 'Aktif',
                    'expiring_soon' => 'Segera Habis (< 30 Hari)',
                    'expired' => 'Habis Kontrak',
                    default => 'Tidak Diketahui',
                };
            }
        );
    }

    /**
     * Tailwind CSS badge classes based on status
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->calculated_status) {
                    'renewed' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 border border-indigo-200',
                    'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 border border-emerald-200',
                    'expiring_soon' => 'bg-amber-50 text-amber-700 ring-amber-600/20 border border-amber-200',
                    'expired' => 'bg-rose-50 text-rose-700 ring-rose-600/20 border border-rose-200',
                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20 border border-gray-200',
                };
            }
        );
    }

    /**
     * Days difference relative to today
     */
    protected function remainingDays(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (! $this->end_date) {
                    return 0;
                }

                $today = Carbon::today();
                $endDate = $this->end_date->copy()->startOfDay();

                return (int) $today->diffInDays($endDate, false);
            }
        );
    }

    /**
     * Descriptive string for remaining days
     */
    protected function remainingDaysText(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->status === 'renewed') {
                    return 'Sudah diperpanjang ke kontrak baru';
                }

                $days = $this->remaining_days;

                if ($days > 0) {
                    return "Sisa {$days} hari";
                }

                if ($days === 0) {
                    return 'Habis hari ini';
                }

                $absoluteDays = abs($days);

                return "Lewat {$absoluteDays} hari lalu";
            }
        );
    }

    /**
     * Total contract duration in months
     */
    protected function durationInMonths(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (! $this->start_date || ! $this->end_date) {
                    return 0;
                }

                return (int) $this->start_date->diffInMonths($this->end_date);
            }
        );
    }

    /**
     * Total contract duration in days
     */
    protected function durationInDays(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (! $this->start_date || ! $this->end_date) {
                    return 0;
                }

                return (int) $this->start_date->diffInDays($this->end_date);
            }
        );
    }

    /**
     * Progress percentage of contract elapsed
     */
    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (! $this->start_date || ! $this->end_date) {
                    return 0;
                }

                $totalDays = $this->start_date->diffInDays($this->end_date);
                if ($totalDays <= 0) {
                    return 100;
                }

                $daysElapsed = $this->start_date->diffInDays(Carbon::today(), false);
                if ($daysElapsed <= 0) {
                    return 0;
                }

                if ($daysElapsed >= $totalDays) {
                    return 100;
                }

                return (int) round(($daysElapsed / $totalDays) * 100);
            }
        );
    }
}
