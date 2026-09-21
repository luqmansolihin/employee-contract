<?php

namespace Database\Factories;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['Laki-laki', 'Perempuan']);
        $firstName = $gender === 'Laki-laki' ? fake('id_ID')->firstNameMale() : fake('id_ID')->firstNameFemale();
        $lastName = fake('id_ID')->lastName();
        $name = "{$firstName} {$lastName}";

        $positions = [
            'Software Engineer',
            'Frontend Developer',
            'HR Officer',
            'Finance & Accounting Staff',
            'Marketing Specialist',
            'Customer Support',
            'Warehouse Staff',
            'Quality Assurance',
            'Administrative Staff',
            'Graphic Designer',
        ];

        $branches = [
            'Jakarta Pusat',
            'Jakarta Selatan',
            'Bandung',
            'Surabaya',
            'Semarang',
            'Yogyakarta',
            'Medan',
            'Bali',
        ];

        $birthPlaces = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Solo', 'Malang', 'Bogor'];

        $joinDate = Carbon::today()->subMonths(fake()->numberBetween(3, 18));
        $contractEndDate = $joinDate->copy()->addMonths(fake()->randomElement([6, 12, 24]));
        $position = fake()->randomElement($positions);
        $branch = fake()->randomElement($branches);

        return [
            'name' => $name,
            'ktp_number' => (string) fake()->numerify('3201############'),
            'gender' => $gender,
            'birth_place' => fake()->randomElement($birthPlaces),
            'birth_date' => Carbon::today()->subYears(fake()->numberBetween(21, 45))->subDays(fake()->numberBetween(0, 360)),
            'address' => fake('id_ID')->address(),
            'first_join_date' => $joinDate->toDateString(),
            'current_position' => $position,
            'current_branch' => $branch,
            'current_contract_end_date' => $contractEndDate->toDateString(),
        ];
    }

    /**
     * Configure the model factory with default initial contract.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Employee $employee) {
            // Only create initial contract if none was created by state
            if ($employee->contracts()->count() === 0) {
                $employee->contracts()->create([
                    'contract_sequence' => 1,
                    'contract_number' => '001/PKWT/'.fake()->numerify('###/2025'),
                    'position' => $employee->current_position,
                    'branch' => $employee->current_branch,
                    'start_date' => $employee->first_join_date,
                    'end_date' => $employee->current_contract_end_date,
                    'status' => 'active',
                    'notes' => 'Kontrak awal saat pertama kali bergabung.',
                ]);
            }
        });
    }

    /**
     * Active contract state (> 30 days remaining).
     */
    public function active(): static
    {
        return $this->state(function () {
            $joinDate = Carbon::today()->subMonths(fake()->numberBetween(1, 6));

            return [
                'first_join_date' => $joinDate->toDateString(),
                'current_contract_end_date' => Carbon::today()->addMonths(fake()->numberBetween(2, 12))->toDateString(),
            ];
        });
    }

    /**
     * Expiring soon state (within 30 days).
     */
    public function expiringSoon(): static
    {
        return $this->state(function () {
            $joinDate = Carbon::today()->subMonths(11);

            return [
                'first_join_date' => $joinDate->toDateString(),
                'current_contract_end_date' => Carbon::today()->addDays(fake()->numberBetween(3, 28))->toDateString(),
            ];
        });
    }

    /**
     * Expired contract state.
     */
    public function expired(): static
    {
        return $this->state(function () {
            $joinDate = Carbon::today()->subMonths(14);

            return [
                'first_join_date' => $joinDate->toDateString(),
                'current_contract_end_date' => Carbon::today()->subDays(fake()->numberBetween(5, 60))->toDateString(),
            ];
        });
    }

    /**
     * State for employee with renewed contract history.
     */
    public function withRenewals(int $previousContractsCount = 1): static
    {
        return $this->afterCreating(function (Employee $employee) use ($previousContractsCount) {
            // Clear initially generated contract
            $employee->contracts()->delete();

            $currentJoin = Carbon::parse($employee->first_join_date);
            $startDate = $currentJoin->copy();

            // Create previous contracts (renewed)
            for ($i = 1; $i <= $previousContractsCount; $i++) {
                $endDate = $startDate->copy()->addMonths(12);

                $employee->contracts()->create([
                    'contract_sequence' => $i,
                    'contract_number' => sprintf('%03d/PKWT/HRD/%d', $i, $startDate->year),
                    'position' => $i === 1 ? 'Junior '.$employee->current_position : $employee->current_position,
                    'branch' => $employee->current_branch,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'status' => 'renewed',
                    'notes' => "Periode kontrak ke-{$i} telah selesai dan diperpanjang dengan hasil evaluasi baik.",
                ]);

                $startDate = $endDate->copy()->addDay();
            }

            // Create current active contract
            $latestSequence = $previousContractsCount + 1;
            $employee->contracts()->create([
                'contract_sequence' => $latestSequence,
                'contract_number' => sprintf('%03d/PKWT/HRD/%d', $latestSequence, $startDate->year),
                'position' => $employee->current_position,
                'branch' => $employee->current_branch,
                'start_date' => $startDate->toDateString(),
                'end_date' => $employee->current_contract_end_date,
                'status' => 'active',
                'notes' => "Perpanjangan kontrak ke-{$previousContractsCount} (Kontrak #{$latestSequence}).",
            ]);
        });
    }
}
