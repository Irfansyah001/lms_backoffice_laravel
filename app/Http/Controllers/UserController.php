<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $users = User::query()
            ->when($search, function ($query, string $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'pustakawan'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        User::create([
            ...$data,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'pustakawan'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        if ($user->is(auth()->user()) && $data['status'] === 'nonaktif') {
            return back()->withErrors(['status' => 'Anda tidak bisa menonaktifkan akun sendiri.']);
        }

        if (blank($data['password'])) {
            unset($data['password']);
        }

        $user->update([
            ...$data,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak bisa menghapus akun sendiri.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
