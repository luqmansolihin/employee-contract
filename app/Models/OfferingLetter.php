<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfferingLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'letter_number',
        'offer_date',
        'contract_type',
        'position',
        'branch',
        'proposed_start_date',
        'proposed_end_date',
        'basic_salary',
        'allowance',
        'valid_until',
        'status',
        'terms',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'offer_date' => 'date',
            'proposed_start_date' => 'date',
            'proposed_end_date' => 'date',
            'valid_until' => 'date',
            'basic_salary' => 'decimal:2',
            'allowance' => 'decimal:2',
        ];
    }

    /**
     * Relationship: Offering Letter belongs to an Employee.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relationship: Offering letter may have an official contract generated.
     */
    public function contract(): HasOne
    {
        return $this->hasOne(EmployeeContract::class, 'offering_letter_id');
    }

    /**
     * Status human-readable label.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->status) {
                'draft' => 'Draft',
                'sent' => 'Terkirim',
                'accepted' => 'Diterima',
                'rejected' => 'Ditolak',
                default => ucfirst($this->status),
            }
        );
    }

    /**
     * Badge CSS class for status.
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->status) {
                'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
                'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            }
        );
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
            get: fn(): string => 'Rp ' . number_format((float) ($this->basic_salary ?? 0), 0, ',', '.')
        );
    }

    /**
     * Formatted allowance.
     */
    protected function formattedAllowance(): Attribute
    {
        return Attribute::make(
            get: fn(): string => 'Rp ' . number_format((float) ($this->allowance ?? 0), 0, ',', '.')
        );
    }

    /**
     * Formatted total compensation.
     */
    protected function formattedTotalCompensation(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $total = (float) ($this->basic_salary ?? 0) + (float) ($this->allowance ?? 0);

                return 'Rp ' . number_format($total, 0, ',', '.');
            }
        );
    }
}
