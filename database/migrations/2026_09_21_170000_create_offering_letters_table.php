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
            $table->date('offer_date')->index();
            $table->enum('contract_type', ['PKWT', 'MT', 'MAGANG'])->default('PKWT')->index();
            $table->string('position');
            $table->string('branch');
            $table->date('proposed_start_date');
            $table->date('proposed_end_date');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowance', 15, 2)->default(0);
            $table->date('valid_until')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft')->index();
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
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
