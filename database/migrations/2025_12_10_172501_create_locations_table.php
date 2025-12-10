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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            // Basic location information
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('province', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('IT');

            // Location details
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            // Media handling
            $table->string('main_photo_path')->nullable();

            // Additional metadata
            $table->json('features')->nullable()->comment('JSON object containing location features like parking, electricity, etc.');
            $table->text('notes')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['city', 'province']);
            $table->index(['latitude', 'longitude']);
        });

        // Create a separate table for location photos (one-to-many relationship)
        Schema::create('location_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['location_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_photos');
        Schema::dropIfExists('locations');
    }
};
