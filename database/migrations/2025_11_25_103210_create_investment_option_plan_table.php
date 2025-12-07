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
        Schema::create('investment_option_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('investment_plan_id')->constrained();
            $table->foreignUuid('investment_option_id')->constrained();
            $table->integer('allocation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_option_plan');
    }
};
