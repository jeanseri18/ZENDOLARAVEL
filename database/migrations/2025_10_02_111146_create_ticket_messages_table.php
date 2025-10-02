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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->boolean('is_admin_reply')->default(false);
            $table->boolean('is_internal_note')->default(false);
            $table->json('attachments')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['ticket_id', 'created_at']);
            $table->index('is_admin_reply');
            $table->index('is_internal_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
