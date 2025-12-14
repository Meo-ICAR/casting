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
        Schema::create('project_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // Location details
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location_type')->nullable(); // e.g., 'indoor', 'outdoor', 'studio', 'public_space', etc.

            // Address information
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('province', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('IT');

            // Location specifications
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Shooting details
            $table->date('shooting_date')->nullable();
            $table->time('shooting_time_from')->nullable();
            $table->time('shooting_time_to')->nullable();

            // Status and permissions
            $table->string('status')->default('pending')->comment('pending, requested, confirmed, completed, cancelled');
            $table->boolean('permission_required')->default(false);
            $table->text('permission_details')->nullable();

            // Additional information
            $table->text('notes')->nullable();
            $table->text('specifications')->nullable(); // JSON field for additional specifications

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['project_id', 'status']);
            $table->index(['city', 'shooting_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_locations');
    }
};
