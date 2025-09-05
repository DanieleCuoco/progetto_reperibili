<?php

namespace App\Http\Controllers;

use App\Models\Reperibile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReperibileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reperibili = Reperibile::all();
        return view('admin.reperibili.index', compact('reperibili'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reperibili.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:reperibiles',
            'password' => 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:reperibiles',
            'phone' => 'nullable',
            'department' => 'required',
            'is_active' => 'boolean'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        Reperibile::create($validated);

        return redirect()->route('admin.reperibili.index')
            ->with('success', 'Reperibile creato con successo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reperibile $reperibile)
    {
        return view('admin.reperibili.show', compact('reperibile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reperibile $reperibile)
    {
        return view('admin.reperibili.edit', compact('reperibile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reperibile $reperibile)
    {
        $validated = $request->validate([
            'username' => 'required|unique:reperibiles,username,' . $reperibile->id,
            'password' => 'nullable|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:reperibiles,email,' . $reperibile->id,
            'phone' => 'nullable',
            'department' => 'required',
            'is_active' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $reperibile->update($validated);

        return redirect()->route('admin.reperibili.index')
            ->with('success', 'Reperibile aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reperibile $reperibile)
    {
        $reperibile->delete();

        return redirect()->route('admin.reperibili.index')
            ->with('success', 'Reperibile eliminato con successo');
    }
}