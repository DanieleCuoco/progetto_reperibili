<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reperibile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReperibileAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('reperibile.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('reperibile')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('reperibile.dashboard');
        }

        return back()->withErrors([
            'email' => 'Le credenziali fornite non sono corrette.',
        ]);
    }

    public function dashboard()
    {
        // Controllo autenticazione reperibile
        $reperibile = Auth::guard('reperibile')->user();
        
        if (!$reperibile) {
            return redirect()->route('reperibile.login');
        }
        
        return response()
            ->view('reperibile.dashboard', compact('reperibile'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('reperibile')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('reperibile.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}