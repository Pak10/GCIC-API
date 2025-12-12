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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUUid('account_type_id')->constrained();
            $table->foreignUuid('user_id')->nullable()->constrained();
            $table->foreignUuid('investment_plan_id')->constrained();
            $table->uuid('account_identifier');
            $table->bigIncrements('account_number')->from(1000); 
            $table->decimal('balance', 30,2)->default(0);
            $table->decimal('total_deposit',30,2)->default(0);
            $table->decimal('interest_gained', 30,2)->default(0);
            $table->decimal('referral_commission_earned', 30,2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
