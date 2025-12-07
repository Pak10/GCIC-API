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
        Schema::create('user_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('category', ['member', 'administrator']);
            $table->uuid('registration_reference');
            $table->boolean('self_registration')->default(true);
            $table->json('data');
            $table->enum('status', ['pending', 'rejected', 'reviewed', 'approved'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_registrations');
    }
};
