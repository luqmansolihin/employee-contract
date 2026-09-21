<?php

namespace App\Models;

use App\Services\LetterNumberService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAddendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_contract_id',
        'addendum_number',
        'addendum_sequence',
        'issue_date',
        'effective_date',
        'previous_end_date',
        'new_end_date',
        'previous_position',
        'new_position',
        'previous_salary',
        'new_salary',
        'amendment_reason',
        'clause_changes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'effective_date' => 'date',
            'previous_end_date' => 'date',
            'new_end_date' => 'date',
            'addendum_sequence' => 'integer',
            'previous_salary' => 'decimal:2',
            'new_salary' => 'decimal:2',
        ];
    }

    /**
     * Relationship: Addendum belongs to an Employee.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relationship: Addendum belongs to a Contract.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(EmployeeContract::class, 'employee_contract_id');
    }

    /**
     * Sequence label in Roman format (e.g. Adendum I, Adendum II).
     */
    protected function sequenceLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => 'Adendum ' . LetterNumberService::romanMonth($this->addendum_sequence ?? 1)
        );
    }

    /**
     * Formatted previous salary.
     */
    protected function formattedPreviousSalary(): Attribute
    {
        return Attribute::make(
            get: fn(): ?string => $this->previous_salary !== null
                ? 'Rp ' . number_format((float) $this->previous_salary, 0, ',', '.')
                : null
        );
    }

    /**
     * Formatted new salary.
     */
    protected function formattedNewSalary(): Attribute
    {
        return Attribute::make(
            get: fn(): ?string => $this->new_salary !== null
                ? 'Rp ' . number_format((float) $this->new_salary, 0, ',', '.')
                : null
        );
    }
}
