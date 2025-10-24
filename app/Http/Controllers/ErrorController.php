<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Mostra la pagina 404 personalizzata
     */
    public function notFound()
    {
        return response()->view('errors.404', [], 404);
    }
    
    /**
     * Mostra una pagina di errore generico
     */
    public function error($code = 500)
    {
        $message = $this->getErrorMessage($code);
        
        return response()->view('errors.generic', [
            'code' => $code,
            'message' => $message
        ], $code);
    }
    
    /**
     * Ottiene il messaggio di errore basato sul codice
     */
    private function getErrorMessage($code)
    {
        $messages = [
            403 => 'Accesso negato - Non hai i permessi per accedere a questa risorsa',
            404 => 'Pagina non trovata - La risorsa richiesta non esiste',
            500 => 'Errore interno del server - Qualcosa è andato storto',
            503 => 'Servizio non disponibile - Il server è temporaneamente in manutenzione'
        ];
        
        return $messages[$code] ?? 'Si è verificato un errore imprevisto';
    }
}