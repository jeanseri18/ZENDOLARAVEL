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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_reference', 50)->unique();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('traveler_id')->nullable();
            $table->enum('transaction_type', ['delivery_fee', 'commission', 'refund', 'wallet_topup', 'wallet_withdrawal', 'penalty']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('commission_rate', 5, 2)->default(0.00);
            $table->decimal('commission_amount', 12, 2)->default(0.00);
            $table->decimal('net_amount', 12, 2);
            $table->enum('payment_method', ['mobile_money', 'orange_money', 'mtn_money', 'moov_money', 'bank_transfer', 'cash', 'card', 'wallet']);
            $table->string('payment_provider', 50)->nullable();
            $table->enum('transaction_status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'disputed'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('external_reference', 100)->nullable();
            $table->string('receipt_url', 255)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('set null');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('traveler_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('processed_by')->references('user_id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('transaction_reference');
            $table->index('package_id');
            $table->index('sender_id');
            $table->index('traveler_id');
            $table->index('transaction_type');
            $table->index('transaction_status');
            $table->index('payment_method');
            $table->index('created_at');
            $table->index('amount');
            $table->index('external_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
