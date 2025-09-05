<?php

namespace App\Http\Controllers;

use App\Models\TurnoReperibilita;
use Illuminate\Http\Request;

class AdminTurniController extends Controller
{
    public function index()
    {
        $turniPendenti = TurnoReperibilita::where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->with('reperibile')
            ->get();
            
        $turniApprovati = TurnoReperibilita::where('is_approved', true)
            ->orderBy('data_inizio', 'desc')
            ->with('reperibile')
            ->take(10)
            ->get();
            
        return view('admin.turni.index', compact('turniPendenti', 'turniApprovati'));
    }
    
    public function approve(TurnoReperibilita $turno)
    {
        $turno->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'Turno di reperibilità approvato con successo!');
    }
    
    public function reject(TurnoReperibilita $turno)
    {
        $turno->delete();
        
        return redirect()->back()->with('success', 'Turno di reperibilità rifiutato e rimosso con successo!');
    }
}