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
        Schema::table('profiles', function (Blueprint $table) {
            $table->enum('scene_nudo', ['no', 'parziale', 'si'])->default('no')->after('is_visible');
            $table->boolean('consenso_privacy')->default(false)->after('scene_nudo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['scene_nudo', 'consenso_privacy']);
        });
    }
};
