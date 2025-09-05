<?php

namespace App\Http\Controllers;

use App\Models\Reparto;
use Illuminate\Http\Request;

class RepartoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reparti = Reparto::all();
        return view('admin.reparti.index', compact('reparti'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $icons = config('icons.reparti');
        return view('admin.reparti.create', compact('icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|unique:repartos',
            'descrizione' => 'nullable',
            'codice' => 'required|unique:repartos',
            'is_active' => 'boolean',
            'icon_name' => 'required|string',
            'icon_class' => 'required|string'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Salviamo l'icona e la classe come attributi della sessione
        session(['icon_' . $validated['codice'] => [
            'icon' => $validated['icon_name'],
            'class' => $validated['icon_class']
        ]]);

        // Rimuoviamo i campi icon_name e icon_class prima di salvare nel database
        unset($validated['icon_name']);
        unset($validated['icon_class']);

        Reparto::create($validated);

        return redirect()->route('admin.reparti.index')
            ->with('success', 'Reparto creato con successo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reparto $reparto)
    {
        return view('admin.reparti.show', compact('reparto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reparto $reparto)
    {
        $icons = config('icons.reparti');
        
        // Recuperiamo l'icona salvata nella sessione, se esiste
        $selectedIcon = session('icon_' . $reparto->codice, [
            'icon' => 'bi-diagram-3',
            'class' => 'icon-default'
        ]);
        
        return view('admin.reparti.edit', compact('reparto', 'icons', 'selectedIcon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reparto $reparto)
    {
        $validated = $request->validate([
            'nome' => 'required|unique:repartos,nome,' . $reparto->id,
            'descrizione' => 'nullable',
            'codice' => 'required|unique:repartos,codice,' . $reparto->id,
            'is_active' => 'boolean',
            'icon_name' => 'required|string',
            'icon_class' => 'required|string'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Aggiorniamo l'icona nella sessione
        session(['icon_' . $validated['codice'] => [
            'icon' => $validated['icon_name'],
            'class' => $validated['icon_class']
        ]]);

        // Se il codice è cambiato, rimuoviamo la vecchia chiave dalla sessione
        if ($reparto->codice !== $validated['codice']) {
            session()->forget('icon_' . $reparto->codice);
        }

        // Rimuoviamo i campi icon_name e icon_class prima di salvare nel database
        unset($validated['icon_name']);
        unset($validated['icon_class']);

        $reparto->update($validated);

        return redirect()->route('admin.reparti.index')
            ->with('success', 'Reparto aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reparto $reparto)
{
    // Verifica se ci sono reperibili associati
    if ($reparto->reperibili()->count() > 0) {
        return redirect()->route('admin.reparti.index')
            ->with('error', 'Impossibile eliminare il reparto: ci sono reperibili associati');
    }
    
    // Rimuoviamo l'icona dalla sessione
    session()->forget('icon_' . $reparto->codice);
    
    try {
        $reparto->delete();
        return redirect()->route('admin.reparti.index')
            ->with('success', 'Reparto eliminato con successo');
    } catch (\Exception $e) {
        return redirect()->route('admin.reparti.index')
            ->with('error', 'Errore durante l\'eliminazione del reparto: ' . $e->getMessage());
    }
}
}