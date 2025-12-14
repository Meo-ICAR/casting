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
        // 1. PROGETTI
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Casting Director

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('production_company')->nullable();

            // Tipo: feature_film, commercial, tv_series, short
            $table->string('type')->default('feature_film')->index();

            // Stato: casting, production, wrapped
            $table->string('status')->default('casting')->index();

            $table->date('start_date')->nullable();
            $table->timestamps();
        });

        // 2. RUOLI
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();

            $table->string('name'); // es. "Protagonista", "Antagonista"
            $table->text('description')->nullable();

            // Requisiti per Auto-Matching (es. range età, skills richieste)
            $table->json('requirements')->nullable();

            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();

            $table->boolean('is_open')->default(true); // Accetta candidature?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('projects');
    }
};
