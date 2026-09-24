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
        Schema::table('contract_addendums', function (Blueprint $table) {
            $table->string('kode', 50)->nullable()->after('addendum_number');
            $table->string('bidang', 255)->nullable()->after('new_position');
            $table->string('branch', 255)->nullable()->after('bidang');
            $table->string('supervisor_name', 255)->nullable()->after('clause_changes');
            $table->string('supervisor_position', 255)->nullable()->after('supervisor_name');
            $table->text('office_address')->nullable()->after('supervisor_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_addendums', function (Blueprint $table) {
            $table->dropColumn([
                'kode',
                'bidang',
                'branch',
                'supervisor_name',
                'supervisor_position',
                'office_address',
            ]);
        });
    }
};
