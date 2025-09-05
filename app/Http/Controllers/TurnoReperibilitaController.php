<?php

namespace App\Http\Controllers;

use App\Models\TurnoReperibilita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoReperibilitaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'data_inizio' => 'required|date',
            'data_fine' => 'required|date|after_or_equal:data_inizio',
            'ora_inizio' => 'required',
            'ora_fine' => 'required',
            'note' => 'nullable|string',
        ]);
        
        $reperibile = Auth::guard('reperibile')->user();
        
        TurnoReperibilita::create([
            'reperibile_id' => $reperibile->id,
            'data_inizio' => $request->data_inizio,
            'data_fine' => $request->data_fine,
            'ora_inizio' => $request->ora_inizio,
            'ora_fine' => $request->ora_fine,
            'note' => $request->note,
            'is_approved' => false, // Imposta a false di default
        ]);
        
        return redirect()->back()->with('success', 'Turno di reperibilità inserito con successo! Sarà visibile nel calendario dopo l\'approvazione dell\'amministratore.');
    }
}