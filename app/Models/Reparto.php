<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descrizione',
        'codice',
        'is_active'
    ];

    // Relazione con i reperibili
    public function reperibili()
    {
        return $this->hasMany(Reperibile::class, 'department', 'codice');
    }
    
    // Specifica quale campo usare per il route model binding
    public function getRouteKeyName()
    {
        return 'id';
    }
}