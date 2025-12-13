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
        Schema::table('quotations', function (Blueprint $table) {
            // Add new column first
            $table->foreignId('project_service_id')->after('id')
                  ->nullable()
                  ->constrained('project_services')
                  ->onDelete('cascade');

            // Update existing data if needed (you might need to adjust this based on your data)
            // This is just a placeholder - you'll need to implement the actual data migration

            // Add foreign key constraint after data is migrated
            $table->foreignId('project_service_id')->nullable(false)->change();

            // Remove old foreign keys and columns
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');

            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Add back the old columns
            $table->foreignId('project_id')->after('id');
            $table->foreignId('service_id')->after('project_id');

            // Add back the old foreign key constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            // Migrate data back if needed (you'll need to implement this)

            // Drop the new column
            $table->dropForeign(['project_service_id']);
            $table->dropColumn('project_service_id');
        });
    }
};
