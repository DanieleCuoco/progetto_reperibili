<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reperibile extends Authenticatable
{
    use Notifiable;

    // Aggiungi questa riga per specificare il nome corretto della tabella
    protected $table = 'reperibiles';

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'phone',
        'department',
        'is_active'
    ];

    protected $hidden = [
        'password',
    ];

    // Relazione con il reparto
    public function reparto()
    {
        return $this->belongsTo(Reparto::class, 'department', 'codice');
    }
    
    // Relazione con i turni di reperibilità
    public function turni()
    {
        return $this->hasMany(TurnoReperibilita::class);
    }
}