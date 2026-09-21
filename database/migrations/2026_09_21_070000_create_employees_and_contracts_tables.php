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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ktp_number', 16)->unique();
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->date('first_join_date')->index();

            // Cached current contract info for fast index, search & filter queries
            $table->string('current_position')->index();
            $table->string('current_branch')->index();
            $table->date('current_contract_end_date')->index();

            $table->timestamps();
        });

        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->unsignedInteger('contract_sequence')->default(1)->index();
            $table->string('contract_number')->nullable();
            $table->string('position');
            $table->string('branch');
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->enum('status', ['active', 'renewed', 'expired'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('employees');
    }
};
