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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error', 'system']);
            $table->enum('category', ['general', 'package', 'transaction', 'delivery', 'verification', 'promotion']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->foreignId('related_package_id')->nullable()->constrained('packages', 'package_id')->onDelete('set null');
            $table->foreignId('related_transaction_id')->nullable()->constrained('transactions', 'transaction_id')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['user_id', 'is_read', 'is_deleted']);
            $table->index(['type', 'category']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
