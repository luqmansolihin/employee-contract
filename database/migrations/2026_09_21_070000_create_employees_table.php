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
            $table->date('first_join_date')->nullable()->index();

            // Cached current contract info for fast index, search & filter queries
            $table->string('current_position')->nullable()->index();
            $table->string('current_branch')->nullable()->index();
            $table->date('current_contract_end_date')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
