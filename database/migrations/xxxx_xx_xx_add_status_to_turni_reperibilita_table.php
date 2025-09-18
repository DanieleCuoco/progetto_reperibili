<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('turni_reperibilita', function (Blueprint $table) {
            $table->enum('status', ['nuovo', 'modifica', 'cancellazione'])->default('nuovo')->after('is_approved');
            $table->text('motivo_modifica')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turni_reperibilita', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('motivo_modifica');
        });
    }
};