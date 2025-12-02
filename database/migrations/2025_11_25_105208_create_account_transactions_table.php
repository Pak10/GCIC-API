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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->constrained();
            $table->foreignUuid('transaction_type_id')->constrained();
            $table->decimal('amount',30,2);
            $table->dateTime('date_of_transaction');
            $table->foreignUuid('approved_by')->nullable()->constrained('users');
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('transaction_status_id')->nullable()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
