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
        Schema::create('promo_code_usage', function (Blueprint $table) {
            $table->id('usage_id');
            $table->unsignedBigInteger('promo_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->decimal('discount_applied', 10, 2);
            $table->timestamp('used_at')->useCurrent();
            
            // Foreign key constraints
            $table->foreign('promo_id')->references('promo_id')->on('promo_codes')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('set null');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('set null');
            
            // Unique constraint
            $table->unique(['promo_id', 'user_id', 'package_id'], 'unique_user_promo');
            
            // Indexes
            $table->index('promo_id');
            $table->index('user_id');
            $table->index('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_usage');
    }
};
