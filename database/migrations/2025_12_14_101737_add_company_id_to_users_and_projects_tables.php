<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

  /**
     * Run the migrations.
     */
return new class extends Migration
{
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
    });
    Schema::table('projects', function (Blueprint $table) {
        $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
    });
    Schema::table('locations', function (Blueprint $table) {
        $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
    });
    Schema::table('services', function (Blueprint $table) {
        $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
    });
}
public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
    });
    Schema::table('projects', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
    });
    Schema::table('locations', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
    });
    Schema::table('services', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
    });
}
};
