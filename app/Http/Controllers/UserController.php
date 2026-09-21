<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalCount = User::query()->count();
        $adminCount = User::query()->where('role', 'admin')->count();
        $staffCount = User::query()->where('role', 'staff')->count();

        return view('users.index', compact('users', 'totalCount', 'adminCount', 'staffCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = User::create($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', "Pengguna {$user->name} ({$user->role_label}) berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        // Prevent demoting the only admin left
        if ($user->isAdmin() && $request->input('role') !== 'admin') {
            $totalAdmins = User::query()->where('role', 'admin')->count();
            if ($totalAdmins <= 1) {
                return back()
                    ->withInput()
                    ->with('error', 'Tidak dapat mengubah peran karena akun ini adalah satu-satunya Super Admin yang tersisa.');
            }
        }

        $data = $request->safe()->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        // Extra safeguard against self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Extra safeguard against deleting the last admin
        if ($user->isAdmin() && User::query()->where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus akun karena ini adalah satu-satunya Super Admin di sistem.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "Akun pengguna {$name} berhasil dihapus dari sistem.");
    }
}
