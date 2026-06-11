<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RackController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $racks = Rack::withCount('books')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('racks.index', compact('racks', 'search'));
    }

    public function create(): View
    {
        return view('racks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:racks,name'],
            'description' => ['nullable', 'string'],
        ]);

        Rack::create([
            ...$data,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('racks.index')->with('success', 'Rak berhasil ditambahkan.');
    }

    public function edit(Rack $rack): View
    {
        return view('racks.edit', compact('rack'));
    }

    public function update(Request $request, Rack $rack): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('racks')->ignore($rack)],
            'description' => ['nullable', 'string'],
        ]);

        $rack->update([
            ...$data,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('racks.index')->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Rack $rack): RedirectResponse
    {
        if ($rack->books()->exists()) {
            return back()->withErrors(['rack' => 'Rak masih digunakan oleh buku.']);
        }

        $rack->delete();

        return redirect()->route('racks.index')->with('success', 'Rak berhasil dihapus.');
    }
}
