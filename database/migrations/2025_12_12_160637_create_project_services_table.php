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
        Schema::create('project_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // Service details
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('service_type')->nullable(); // e.g., 'catering', 'equipment', 'crew', etc.

            // Requirements and specifications
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable(); // e.g., 'hour', 'day', 'piece', etc.
            $table->decimal('estimated_cost', 10, 2)->nullable();

            // Status and timing
            $table->string('status')->default('pending')->comment('pending, requested, confirmed, completed, cancelled');
            $table->date('needed_from')->nullable();
            $table->date('needed_until')->nullable();

            // Additional information
            $table->text('notes')->nullable();
            $table->text('specifications')->nullable(); // JSON field for additional specifications

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['project_id', 'status']);
            $table->index(['service_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_services');
    }
};
