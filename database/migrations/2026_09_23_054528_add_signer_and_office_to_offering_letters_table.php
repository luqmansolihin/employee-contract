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
        Schema::table('offering_letters', function (Blueprint $table) {
            $table->string('supervisor_name', 255)->nullable()->after('notes');
            $table->string('supervisor_position', 255)->nullable()->after('supervisor_name');
            $table->text('office_address')->nullable()->after('supervisor_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_letters', function (Blueprint $table) {
            $table->dropColumn(['supervisor_name', 'supervisor_position', 'office_address']);
        });
    }
};
