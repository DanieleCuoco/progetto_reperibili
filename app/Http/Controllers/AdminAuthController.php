<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reperibile;
use App\Models\Reparto;

class AdminAuthController extends Controller {
    public function showLoginForm() {
        return view('login');
    }

    public function index() {
        // Conta i reperibili attivi
        $reperibili_attivi = Reperibile::where('is_active', true)->count();
        
        // Conta i reparti
        $reparti = Reparto::count();
        
        // Modifiche in attesa (per ora lasciamo 0 come placeholder)
        $modifiche_in_attesa = 0;
        
        return view('admin.dashboard', compact('reperibili_attivi', 'reparti', 'modifiche_in_attesa'));
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
        return redirect(route('admin.login'));
    }
}