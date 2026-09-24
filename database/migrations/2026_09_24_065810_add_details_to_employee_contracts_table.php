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
        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->string('kode', 50)->nullable()->after('contract_number');
            $table->date('contract_date')->nullable()->after('contract_type');
            $table->string('bidang', 255)->nullable()->after('position');
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
        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->dropColumn([
                'kode',
                'contract_date',
                'bidang',
                'supervisor_name',
                'supervisor_position',
                'office_address',
            ]);
        });
    }
};
