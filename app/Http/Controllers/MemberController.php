<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $members = Member::query()
            ->when($search, function ($query, string $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('members.index', compact('members', 'search'));
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function show(Member $member): View
    {
        $member->load(['createdBy', 'updatedBy', 'borrowings.book']);

        return view('members.show', compact('member'));
    }

    public function store(Request $request): RedirectResponse
    {
        Member::create([
            ...$this->validatedData($request),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $member->update([
            ...$this->validatedData($request),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->borrowings()->exists()) {
            return back()->withErrors(['member' => 'Anggota tidak bisa dihapus karena sudah memiliki riwayat peminjaman.']);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);
    }
}
