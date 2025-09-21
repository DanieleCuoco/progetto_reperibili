<?php

namespace App\Http\Controllers;

use App\Models\Reperibile;
use App\Models\TurnoReperibilita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReperibileController extends Controller
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
        
        $reperibili = Reperibile::all();
        
        return response()
            ->view('admin.reperibili.index', compact('reperibili'))
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
        
        // Ottieni tutti i reparti attivi
        $reparti = \App\Models\Reparto::where('is_active', true)->get();
        
        return response()
            ->view('admin.reperibili.create', compact('reparti'))
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
            ->with('success', 'Reperibile creato con successo')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reperibile $reperibile)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        return response()
            ->view('admin.reperibili.show', compact('reperibile'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reperibile $reperibile)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // Ottieni tutti i reparti attivi
        $reparti = \App\Models\Reparto::where('is_active', true)->get();
        
        return response()
            ->view('admin.reperibili.edit', compact('reperibile', 'reparti'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reperibile $reperibile)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
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
            ->with('success', 'Reperibile aggiornato con successo')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reperibile $reperibile)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        try {
            // Ottieni l'ID del reperibile prima di eliminarlo
            $id = $reperibile->id;
            
            // Elimina prima tutti i turni associati direttamente dalla tabella
            DB::table('turni_reperibilita')->where('reperibile_id', $id)->delete();
            
            // Ora elimina il reperibile direttamente dalla tabella
            DB::table('reperibiles')->where('id', $id)->delete();
            
            return redirect()->route('admin.reperibili.index')
                ->with('success', 'Reperibile eliminato con successo')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            return redirect()->route('admin.reperibili.index')
                ->with('error', 'Errore durante l\'eliminazione del reperibile: ' . $e->getMessage())
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }

    public function deleteReperibile($id)
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        try {
            // Validazione dell'input
            $id = (int)$id; // Forza la conversione a intero
            
            // Elimina prima tutti i turni associati
            $turniEliminati = DB::table('turni_reperibilita')
                ->where('reperibile_id', $id)
                ->delete();
            
            // Poi elimina il reperibile
            $reperibileCancellato = DB::table('reperibiles')
                ->where('id', $id)
                ->delete();
            
            if ($reperibileCancellato) {
                return redirect()->route('admin.reperibili.index')
                    ->with('success', 'Reperibile eliminato con successo')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            } else {
                return redirect()->route('admin.reperibili.index')
                    ->with('error', 'Impossibile eliminare il reperibile')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.reperibili.index')
                ->with('error', 'Errore durante l\'eliminazione del reperibile: ' . $e->getMessage())
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }
}