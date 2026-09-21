<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any employees.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the employee.
     */
    public function view(User $user, Employee $employee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create employees.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the employee.
     */
    public function update(User $user, Employee $employee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can renew contract for the employee.
     */
    public function renew(User $user, Employee $employee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the employee.
     * Only Admin is authorized to delete employee data.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }
}
