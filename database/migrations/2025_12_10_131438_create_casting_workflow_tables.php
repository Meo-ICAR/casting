<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
    {
        // 1. CANDIDATURE (Applications)
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete();

            // Stato del workflow (pending, invited, audition, callback, cast, rejected)
            $table->string('status')->default('pending')->index();

            $table->text('cover_letter')->nullable(); // Messaggio dell'attore
            $table->text('director_notes')->nullable(); // Note private del regista

            $table->timestamps();

            // Vincolo: Un attore non può candidarsi due volte allo stesso ruolo
            $table->unique(['role_id', 'profile_id']);
        });

        // 2. SHORTLIST (Preferiti Generici)
        Schema::create('shortlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Il Director
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete(); // L'Attore selezionato
            $table->timestamps();

            // Evita duplicati nei preferiti
            $table->unique(['user_id', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortlists');
        Schema::dropIfExists('applications');
    }
};
