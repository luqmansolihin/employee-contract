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
        Schema::create('contract_addendums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('employee_contract_id')->constrained('employee_contracts')->cascadeOnDelete();
            $table->string('addendum_number')->unique();
            $table->unsignedInteger('addendum_sequence')->default(1)->index();
            $table->date('issue_date')->index();
            $table->date('effective_date');
            $table->date('previous_end_date');
            $table->date('new_end_date')->index();
            $table->string('previous_position')->nullable();
            $table->string('new_position')->nullable();
            $table->decimal('previous_salary', 15, 2)->nullable();
            $table->decimal('new_salary', 15, 2)->nullable();
            $table->string('amendment_reason')->nullable();
            $table->text('clause_changes')->nullable();
            $table->enum('status', ['active', 'archived'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_addendums');
    }
};
