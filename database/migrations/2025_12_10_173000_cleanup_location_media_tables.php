<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop the location_photos table if it exists
        if (Schema::hasTable('location_photos')) {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Drop the table directly
            Schema::dropIfExists('location_photos');

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Remove the main_photo_path column if it exists
        if (Schema::hasColumn('locations', 'main_photo_path')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->dropColumn('main_photo_path');
            });
        }
    }

    public function down()
    {
        // Recreate the location_photos table
        if (!Schema::hasTable('location_photos')) {
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

        // Add back the main_photo_path column
        if (!Schema::hasColumn('locations', 'main_photo_path')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->string('main_photo_path')->nullable()->after('contact_email');
            });
        }
    }
};
