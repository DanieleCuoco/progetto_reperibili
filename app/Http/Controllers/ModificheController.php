<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoReperibilita;
use Illuminate\Support\Facades\Auth;

class ModificheController extends Controller
{
    public function index()
    {
        // Controllo autenticazione admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // Recupera tutti i turni in attesa di approvazione con reperibile e reparto
        $turniPendenti = TurnoReperibilita::where('is_approved', 0)
                                         ->with(['reperibile.reparto'])
                                         ->orderBy('created_at', 'desc')
                                         ->get();
        
        // Poiché la colonna status potrebbe non esistere in tutti i record,
        // filtriamo i dati in PHP invece che in SQL
        $nuoviTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'nuovo' || $turno->status === null;
        });
        
        $modificheTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'modifica';
        });
        
        $cancellazioniTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'cancellazione';
        });
        
        return response()
            ->view('admin.modifiche.index', compact('nuoviTurni', 'modificheTurni', 'cancellazioniTurni'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function approva($id)
    {
        $turno = TurnoReperibilita::findOrFail($id);
        $turno->is_approved = 1;
        $turno->save();
        
        return redirect()->back()->with('success', 'Turno approvato con successo');
    }
    
    public function rifiuta($id)
    {
        $turno = TurnoReperibilita::findOrFail($id);
        $turno->is_approved = 2; // 2 = rifiutato
        $turno->save();
        
        return redirect()->back()->with('success', 'Turno rifiutato');
    }
}
