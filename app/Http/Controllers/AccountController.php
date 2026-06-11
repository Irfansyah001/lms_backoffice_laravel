<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(): View
    {
        return view('account.settings');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => $data['password'],
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->route('account.settings')
            ->with('success', 'Password akun berhasil diperbarui.');
    }
}
