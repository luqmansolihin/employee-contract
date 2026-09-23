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
            $table->string('kode', 50)->nullable()->after('letter_number');
            $table->string('bidang', 255)->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_letters', function (Blueprint $table) {
            $table->dropColumn(['kode', 'bidang']);
        });
    }
};
