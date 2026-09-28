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
        'kode',
        'addendum_sequence',
        'issue_date',
        'effective_date',
        'previous_end_date',
        'new_end_date',
        'previous_position',
        'new_position',
        'bidang',
        'branch',
        'supervisor_name',
        'supervisor_position',
        'office_address',
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
     * Alias for issue_date as contract_date.
     */
    protected function contractDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->issue_date,
        );
    }
}
