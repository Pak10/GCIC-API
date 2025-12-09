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
        Schema::create('investments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('investment_option_id')->constrained();
            $table->date('date_of_investment');
            $table->uuid('transaction_reference');
            $table->decimal('amount',30,2);
            $table->foreignUuid('created_by')->constrained('users');
            $table->enum('status', ['processing','cancelled','open', 'closed'])->default('processing');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
