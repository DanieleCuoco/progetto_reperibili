<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reparto;
use App\Models\Reperibile;
use Illuminate\Support\Facades\DB;

class UpdateReperibiliDepartments extends Command
{
    /**
     * Il nome e la firma del comando console.
     *
     * @var string
     */
    protected $signature = 'reperibili:update-departments';

    /**
     * La descrizione del comando console.
     *
     * @var string
     */
    protected $description = 'Aggiorna i campi department dei reperibili esistenti per associarli ai codici dei reparti';

    /**
     * Esegui il comando console.
     */
    public function handle()
    {
        $this->info('Inizio aggiornamento dei reperibili...');
        
        // Ottieni tutti i reparti attivi
        $reparti = Reparto::where('is_active', true)->get();
        $repartiMap = [];
        
        foreach ($reparti as $reparto) {
            // Crea una mappa dei nomi dei reparti ai loro codici
            $repartiMap[strtolower($reparto->nome)] = $reparto->codice;
        }
        
        // Ottieni tutti i reperibili
        $reperibili = Reperibile::all();
        $updated = 0;
        $skipped = 0;
        $notFound = 0;
        
        foreach ($reperibili as $reperibile) {
            $currentDepartment = strtolower($reperibile->department);
            
            // Verifica se il department corrisponde a un nome di reparto
            if (array_key_exists($currentDepartment, $repartiMap)) {
                $oldValue = $reperibile->department;
                $reperibile->department = $repartiMap[$currentDepartment];
                $reperibile->save();
                
                $this->info("Reperibile ID {$reperibile->id}: department aggiornato da '{$oldValue}' a '{$reperibile->department}'.");
                $updated++;
            } else {
                // Verifica se il department è già un codice valido
                $existingCode = $reparti->where('codice', $reperibile->department)->first();
                
                if ($existingCode) {
                    $this->line("Reperibile ID {$reperibile->id}: department '{$reperibile->department}' è già un codice valido. Nessuna modifica necessaria.");
                    $skipped++;
                } else {
                    $this->error("Reperibile ID {$reperibile->id}: department '{$reperibile->department}' non corrisponde a nessun reparto. Aggiornamento manuale richiesto.");
                    $notFound++;
                }
            }
        }
        
        $this->info("\nRiepilogo:");
        $this->info("- Reperibili aggiornati: {$updated}");
        $this->info("- Reperibili già corretti: {$skipped}");
        $this->info("- Reperibili da aggiornare manualmente: {$notFound}");
        $this->info("Operazione completata.");
        
        return Command::SUCCESS;
    }
}