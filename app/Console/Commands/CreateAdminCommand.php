<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Crea un nuovo amministratore';

    public function handle()
    {
        $username = $this->ask('Inserisci username per l\'admin');
        $name = $this->ask('Inserisci il nome completo dell\'admin');
        $email = $this->ask('Inserisci email per l\'admin');
        $password = $this->secret('Inserisci password per l\'admin');

        // Validazione input
        if (strlen($password) < 8) {
            $this->error('La password deve essere di almeno 8 caratteri!');
            return 1;
        }

        try {
            Admin::create([
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            $this->info('Amministratore creato con successo!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Errore durante la creazione dell\'admin: ' . $e->getMessage());
            return 1;
        }
    }
}