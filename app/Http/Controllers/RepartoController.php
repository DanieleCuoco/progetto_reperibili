<?php

namespace App\Http\Controllers;

use App\Models\Reparto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepartoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        $reparti = Reparto::all();
        
        return response()
            ->view('admin.reparti.index', compact('reparti'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        $icons = config('icons.reparti');
        
        return response()
            ->view('admin.reparti.create', compact('icons'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
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
            ->with('success', 'Reparto creato con successo')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reparto $reparto)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // Carica i reperibili associati a questo reparto
        $reparto->load('reperibili');
        
        return response()
            ->view('admin.reparti.show', compact('reparto'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reparto $reparto)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        $icons = config('icons.reparti');
        
        // Recuperiamo l'icona salvata nella sessione, se esiste
        $selectedIcon = session('icon_' . $reparto->codice, [
            'icon' => 'bi-diagram-3',
            'class' => 'icon-default'
        ]);
        
        return response()
            ->view('admin.reparti.edit', compact('reparto', 'icons', 'selectedIcon'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reparto $reparto)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
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
            ->with('success', 'Reparto aggiornato con successo')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reparto $reparto)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // Verifica se ci sono reperibili associati
        if ($reparto->reperibili()->count() > 0) {
            return redirect()->route('admin.reparti.index')
                ->with('error', 'Impossibile eliminare il reparto: ci sono reperibili associati')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
        
        // Rimuoviamo l'icona dalla sessione
        session()->forget('icon_' . $reparto->codice);
        
        try {
            $reparto->delete();
            return redirect()->route('admin.reparti.index')
                ->with('success', 'Reparto eliminato con successo')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            return redirect()->route('admin.reparti.index')
                ->with('error', 'Errore durante l\'eliminazione del reparto: ' . $e->getMessage())
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }
}