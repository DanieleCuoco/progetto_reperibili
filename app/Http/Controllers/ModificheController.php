<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoReperibilita;

class ModificheController extends Controller
{
    public function index()
    {
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
        
        return view('admin.modifiche.index', compact('nuoviTurni', 'modificheTurni', 'cancellazioniTurni'));
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
