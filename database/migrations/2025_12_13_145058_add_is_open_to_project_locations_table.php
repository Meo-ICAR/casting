<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('project_locations', function (Blueprint $table) {
            $table->boolean('is_open')->default(true)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('project_locations', function (Blueprint $table) {
            $table->dropColumn('is_open');
        });
    }
};
