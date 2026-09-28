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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('offering_letter_id')->nullable()->constrained('offering_letters')->nullOnDelete();
            $table->unsignedInteger('contract_sequence')->default(1)->index();
            $table->string('contract_number')->nullable();
            $table->string('kode', 50)->nullable();
            $table->enum('contract_type', ['PKWT', 'MT', 'MAGANG'])->default('PKWT')->index();
            $table->date('contract_date')->nullable();
            $table->string('position');
            $table->string('bidang')->nullable();
            $table->string('branch');
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->enum('status', ['active', 'renewed', 'expired'])->default('active')->index();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_position')->nullable();
            $table->text('office_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
