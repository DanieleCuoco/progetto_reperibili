<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    // ... existing code ...

    public function calendar(Request $request)
    {
        // Forza la pulizia della cache per questa vista
        Cache::flush(); // Pulisce tutta la cache
        
        // Forza gli header HTTP per impedire qualsiasi tipo di caching
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data nel passato
        
        // Gestione navigazione tra i mesi
        $month = $request->query('month');
        
        // Forza il refresh della data corrente ogni volta
        $currentMonth = Carbon::now();
        
        if ($month === 'prev') {
            $currentMonth = $currentMonth->subMonth();
        } elseif ($month === 'next') {
            $currentMonth = $currentMonth->addMonth();
        }
        
        // Carica tutti i reparti attivi per il selettore
        $reparti = \App\Models\Reparto::where('is_active', true)->orderBy('nome')->get();
        
        $calendar = [];
        
        // Get the first day of the month and the number of days
        $firstDay = $currentMonth->copy()->firstOfMonth();
        $daysInMonth = $currentMonth->daysInMonth;
        
        // Get the day of week for the first day (0 = Sunday, 6 = Saturday)
        $firstDayOfWeek = $firstDay->dayOfWeek;
        
        // Create calendar array
        $calendar['month'] = $currentMonth->format('F Y');
        $calendar['days'] = [];
        
        // Aggiungi giorni vuoti all'inizio per allineare con il giorno della settimana
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendar['days'][] = [
                'day' => '',
                'isToday' => false,
                'date' => '',
                'turni' => []
            ];
        }
        
        // Ottieni la data di oggi per confronto esplicito
        $today = Carbon::now()->format('Y-m-d');
        
        // Ottieni il reparto selezionato dal filtro
        $selectedDepartment = $request->query('department');
        
        // Carica i turni approvati per il mese corrente
        $startDate = $firstDay->format('Y-m-d');
        $endDate = $firstDay->copy()->addDays($daysInMonth - 1)->format('Y-m-d');
        
        // Query base per i turni approvati nel periodo
        $turniQuery = \App\Models\TurnoReperibilita::where('is_approved', true)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('data_inizio', [$startDate, $endDate])
                    ->orWhereBetween('data_fine', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('data_inizio', '<=', $startDate)
                          ->where('data_fine', '>=', $endDate);
                    });
            })
            ->with('reperibile');
        
        // Applica il filtro per reparto se selezionato
        if ($selectedDepartment) {
            $turniQuery->whereHas('reperibile', function($query) use ($selectedDepartment) {
                $query->where('department', $selectedDepartment);
            });
        }
        
        $turni = $turniQuery->get();
        
        // Fill in the days
        for ($i = 0; $i < $daysInMonth; $i++) {
            $day = $firstDay->copy()->addDays($i);
            $currentDate = $day->format('Y-m-d');
            
            // Confronto esplicito con la data di oggi
            $isToday = ($currentDate === $today);
            
            // Filtra i turni per questa data
            $turniDelGiorno = $turni->filter(function($turno) use ($currentDate) {
                $inizioTurno = $turno->data_inizio;
                $fineTurno = $turno->data_fine;
                return ($currentDate >= $inizioTurno && $currentDate <= $fineTurno);
            })->values();
            
            $dayData = [
                'day' => $day->day,
                'isToday' => $isToday,
                'date' => $currentDate,
                'turni' => $turniDelGiorno
            ];
            
            $calendar['days'][] = $dayData;
        }
        
        // Aggiungi un timestamp casuale come parametro di query per forzare il refresh
        $calendar['timestamp'] = time() . rand(1000, 9999);
        
        // Disabilita il caching per questa vista con header più aggressivi
        return response()
            ->view('calendar', compact('calendar', 'reparti'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
    }
}