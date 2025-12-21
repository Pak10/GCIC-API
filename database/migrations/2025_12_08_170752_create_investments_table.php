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
            $table->string('investment_name');
            $table->mediumText('investment_description')->nullable();
            $table->foreignUuid('investment_option_id')->constrained();
            $table->date('date_of_investment');
            $table->date('expected_recovery_date')->nullable();
            $table->date('date_of_recovery')->nullable();
            $table->uuid('transaction_reference');
            $table->decimal('amount',30,2);
            $table->foreignUuid('created_by')->constrained('users');
            $table->enum('status', ['processing','cancelled','open', 'closed','settling', 'settled'])->default('processing');
            $table->decimal('amount_returned',30,2)->nullable();
            $table->decimal('profit',30,2)->nullable();
            $table->decimal('share_profit',5,2)->nullable();
            $table->foreignUuid('cancelled_by')->nullable()->constrained('users');
            $table->foreignUuid('closed_by')->nullable()->constrained('users');
            $table->foreignUuid('settled_by')->nullable()->constrained('users');
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
