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
        Schema::create('account_investment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->constrained();
            $table->foreignUuid('investment_id')->constrained();
            $table->decimal('amount_invested',30,2);
            $table->decimal('amount_returned',30,2)->nullable();
            $table->decimal('interest_gained',30,2)->nullable();
            $table->decimal('amount_withdrawn',30,2)->nullable();
            $table->decimal('amount_deposited',30,2)->nullable();
            $table->boolean('investment_changed')->default(false);
            $table->boolean('direct_investment')->default(false);
            $table->boolean('has_fixed_interest')->default(false);
            $table->decimal('fixed_interest',5,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_investment');
    }
};
