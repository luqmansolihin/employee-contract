<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'ktp_number',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'first_join_date',
        'current_position',
        'current_branch',
        'current_contract_end_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'first_join_date' => 'date',
            'current_contract_end_date' => 'date',
        ];
    }

    /**
     * Relationship: All contract periods of the employee.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class)->orderBy('contract_sequence', 'desc');
    }

    /**
     * Relationship: Latest active contract period.
     */
    public function latestContract(): HasOne
    {
        return $this->hasOne(EmployeeContract::class)->latestOfMany('contract_sequence');
    }

    /**
     * Status attribute based on current_contract_end_date: active, expiring_soon, expired
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $today = Carbon::today();
                $endDate = $this->current_contract_end_date?->copy()->startOfDay();

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
                return match ($this->status) {
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
                return match ($this->status) {
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
                if (! $this->current_contract_end_date) {
                    return 0;
                }

                $today = Carbon::today();
                $endDate = $this->current_contract_end_date->copy()->startOfDay();

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
     * Age in years based on birth_date
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: function (): ?int {
                return $this->birth_date ? (int) $this->birth_date->age : null;
            }
        );
    }

    /**
     * Scope for searching keyword
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('ktp_number', 'like', "%{$keyword}%")
                ->orWhere('current_position', 'like', "%{$keyword}%")
                ->orWhere('current_branch', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope for contract status filter
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        $today = Carbon::today()->toDateString();
        $thirtyDaysAhead = Carbon::today()->addDays(30)->toDateString();

        return match ($status) {
            'active' => $query->where('current_contract_end_date', '>', $thirtyDaysAhead),
            'expiring_soon' => $query->whereBetween('current_contract_end_date', [$today, $thirtyDaysAhead]),
            'expired' => $query->where('current_contract_end_date', '<', $today),
            default => $query,
        };
    }

    /**
     * Scope for branch filter
     */
    public function scopeFilterBranch(Builder $query, ?string $branch): Builder
    {
        if (blank($branch)) {
            return $query;
        }

        return $query->where('current_branch', $branch);
    }

    /**
     * Scope for position filter
     */
    public function scopeFilterPosition(Builder $query, ?string $position): Builder
    {
        if (blank($position)) {
            return $query;
        }

        return $query->where('current_position', $position);
    }
}
