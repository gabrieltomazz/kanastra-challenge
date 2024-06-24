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
        Schema::create('bankslips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('government_id');
            $table->string('email');
            $table->decimal('debt_amount', 10, 2);
            $table->date('debt_due_date');
            $table->string('debt_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bankslips');
    }
};
