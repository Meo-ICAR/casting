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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // Informazioni base
            $table->string('name'); // Nome azienda/persona
            $table->string('contact_name')->nullable(); // Nome contatto principale
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            // Indirizzo
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('IT');

            // Tipo di servizio (catering, parrucchiere, truccatrice, sartoria, ecc.)
            $table->string('service_type')->index(); // catering, hair, makeup, costume, location, equipment, etc.

            // Informazioni aggiuntive
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable(); // Note interne

            // Stato
            $table->boolean('is_active')->default(true)->index();

            // Collegamento opzionale a utente
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
