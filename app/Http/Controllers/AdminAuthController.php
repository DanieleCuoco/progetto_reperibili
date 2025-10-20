<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reperibile;
use App\Models\Reparto;
use App\Models\TurnoReperibilita;

class AdminAuthController extends Controller {
    public function showLoginForm() {
        return view('login');
    }

    public function index() {
        // Controllo esplicito dell'autenticazione
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // Conta i reperibili attivi
        $reperibili_attivi = Reperibile::where('is_active', true)->count();
        
        // Conta i reparti
        $reparti = Reparto::count();
        
        // Recupera i turni in attesa per le notifiche
        $turniPendenti = TurnoReperibilita::where('is_approved', 0)
                                         ->with(['reperibile.reparto'])
                                         ->whereHas('reperibile') // Solo turni con reperibile esistente
                                         ->orderBy('created_at', 'desc')
                                         ->get();
        
        // Filtra i turni per tipo
        $nuoviTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'nuovo' || $turno->status === null;
        });
        
        $modificheTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'modifica';
        });
        
        $cancellazioniTurni = $turniPendenti->filter(function($turno) {
            return $turno->status === 'cancellazione';
        });
        
        // Conta le modifiche in attesa
        $modifiche_in_attesa = count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni);
        
        return response()
            ->view('admin.dashboard', compact('reperibili_attivi', 'reparti', 'modifiche_in_attesa', 'nuoviTurni', 'modificheTurni', 'cancellazioniTurni'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if(Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        
        return back()->withErrors([
            'username' => 'Le credenziali non sono corrette.',
        ]);
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect(route('admin.login'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}