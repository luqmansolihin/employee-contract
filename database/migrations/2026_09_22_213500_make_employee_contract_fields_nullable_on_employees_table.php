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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('first_join_date')->nullable()->change();
            $table->string('current_position')->nullable()->change();
            $table->string('current_branch')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('first_join_date')->nullable(false)->change();
            $table->string('current_position')->nullable(false)->change();
            $table->string('current_branch')->nullable(false)->change();
        });
    }
};
