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
        Schema::create('offering_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('letter_number')->unique();
            $table->string('kode', 50)->nullable();
            $table->date('offer_date')->index();
            $table->string('position');
            $table->string('bidang')->nullable();
            $table->string('branch');
            $table->date('proposed_start_date');
            $table->date('proposed_end_date');
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft')->index();
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
        Schema::dropIfExists('offering_letters');
    }
};
