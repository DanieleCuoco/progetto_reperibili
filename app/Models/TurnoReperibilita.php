<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoReperibilita extends Model
{
    use HasFactory;
    
    protected $table = 'turni_reperibilita';
    
    protected $fillable = [
        'reperibile_id',
        'reparto_id',
        'data_inizio',
        'data_fine',
        'ora_inizio',
        'ora_fine',
        'note',
        'is_active',
        'is_approved'
    ];
    
    // Relazione con il reperibile
    public function reperibile()
    {
        return $this->belongsTo(Reperibile::class);
    }
    
    // Relazione con il reparto
    public function reparto()
    {
        return $this->belongsTo(Reparto::class);
    }
}