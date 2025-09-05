<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turni_reperibilita', function (Blueprint $table) {
            // Prima rimuoviamo il vincolo esistente
            $table->dropForeign(['reperibile_id']);
            
            // Poi lo ricreiamo con l'opzione onDelete('cascade')
            $table->foreign('reperibile_id')
                  ->references('id')
                  ->on('reperibiles')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('turni_reperibilita', function (Blueprint $table) {
            // Rimuoviamo il vincolo con cascade
            $table->dropForeign(['reperibile_id']);
            
            // Lo ricreiamo senza l'opzione cascade
            $table->foreign('reperibile_id')
                  ->references('id')
                  ->on('reperibiles');
        });
    }
};