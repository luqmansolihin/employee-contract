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
            $table->foreignId('offering_letter_id')->nullable()->after('employee_id')->constrained('offering_letters')->nullOnDelete();
            $table->enum('contract_type', ['PKWT', 'MT', 'MAGANG'])->default('PKWT')->after('contract_number')->index();
            $table->decimal('basic_salary', 15, 2)->nullable()->after('end_date');
            $table->decimal('allowance', 15, 2)->nullable()->default(0)->after('basic_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->dropForeign(['offering_letter_id']);
            $table->dropColumn(['offering_letter_id', 'contract_type', 'basic_salary', 'allowance']);
        });
    }
};
