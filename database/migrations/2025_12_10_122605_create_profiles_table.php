<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Collegamento all'utente (Auth)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // --- Dati Pubblici / Anagrafica ---
            $table->string('stage_name')->nullable()->comment('Nome d\'arte, se diverso dal nome legale');
            $table->string('slug')->unique()->comment('Per URL amichevoli es: casting.com/talent/mario-rossi');
            $table->date('birth_date')->index(); // Indicizzato per calcolo veloce dell'età

            // Usiamo enum o string per il genere (adattabile alle policy di casting attuali)
            $table->string('gender', 50)->index();

            // Localizzazione (Fondamentale per i filtri "Local Hire")
            $table->string('city')->nullable();
            $table->string('country')->default('IT')->index();
            $table->string('province', 2)->nullable(); // Es: RM, MI, NA

            // --- Dati Fisici "Hard" (Quelli sempre filtrati) ---
            $table->unsignedSmallInteger('height_cm')->nullable()->index(); // Altezza in cm
            $table->unsignedSmallInteger('weight_kg')->nullable();

            // --- COLONNE JSON (La potenza di MariaDB) ---

            // 1. Aspetto fisico dettagliato
            // Struttura attesa: {"eyes": "blue", "hair_color": "brown", "hair_length": "short", "skin": "fair", "ethnicity": "caucasian", "tattoos": true}
            $table->json('appearance')->nullable();

            // 2. Misure Sartoriali (Cambiano tra Uomo/Donna)
            // Struttura attesa: {"shoes": 42, "jacket": 50, "waist": 80, "hips": 90, "chest": 100}
            $table->json('measurements')->nullable();

            // 3. Skills, Lingue e Dialetti
            // Struttura attesa: {"languages": ["italian", "english"], "dialects": ["napoletano", "romano"], "skills": ["horse_riding", "fencing"], "driving_license": ["B"]}
            $table->json('capabilities')->nullable();

            // 4. Link Esterni e Social
            // Struttura attesa: {"imdb": "url", "instagram": "url", "showreel_link": "url"}
            $table->json('socials')->nullable();

            // --- Stato Profilo ---
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_represented')->default(false)->comment('Se ha un agente');
            $table->string('agency_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
