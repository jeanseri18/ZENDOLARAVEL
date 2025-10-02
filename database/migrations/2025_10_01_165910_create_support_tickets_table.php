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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->string('ticket_number', 20)->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->enum('category', ['delivery_issue', 'payment_issue', 'account_issue', 'technical_issue', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_response', 'resolved', 'closed'])->default('open');
            $table->string('subject', 200);
            $table->text('description');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('set null');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('set null');
            $table->foreign('assigned_to')->references('user_id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('ticket_number');
            $table->index('user_id');
            $table->index('status');
            $table->index('priority');
            $table->index('category');
            $table->index('assigned_to');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
