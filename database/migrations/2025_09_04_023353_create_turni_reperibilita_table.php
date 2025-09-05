<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turni_reperibilita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reperibile_id')->constrained('reperibiles');
            $table->date('data_inizio');
            $table->date('data_fine');
            $table->time('ora_inizio');
            $table->time('ora_fine');
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turni_reperibilita');
    }
};