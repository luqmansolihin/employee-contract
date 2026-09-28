<?php

namespace App\Models;

use App\Services\ContractDurationService;
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
        'kode',
        'offer_date',
        'position',
        'bidang',
        'branch',
        'proposed_start_date',
        'proposed_end_date',
        'status',
        'supervisor_name',
        'supervisor_position',
        'office_address',
    ];

    protected function casts(): array
    {
        return [
            'offer_date' => 'date',
            'proposed_start_date' => 'date',
            'proposed_end_date' => 'date',
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
     * Human-readable formatted contract duration (e.g. 1 Tahun, 1 Bulan, 1 Tahun 6 Bulan).
     */
    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn(): string => ContractDurationService::format(
                $this->proposed_start_date,
                $this->proposed_end_date
            )
        );
    }
}
