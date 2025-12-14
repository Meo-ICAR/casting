<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Add project_service_id as nullable first


            // Ensure service_id exists and is properly set up
            if (!Schema::hasColumn('quotations', 'service_id')) {
                $table->foreignId('service_id')
                    ->after('project_service_id')
                    ->nullable()
                    ->constrained('services')
                    ->onDelete('cascade');
            }

            // Drop the old project_id if it exists
            if (Schema::hasColumn('quotations', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Add back project_id
            if (!Schema::hasColumn('quotations', 'project_id')) {
                $table->foreignId('project_id')
                    ->after('id')
                    ->nullable()
                    ->constrained('projects')
                    ->onDelete('cascade');
            }

            // Drop the new columns

        });
    }
};
