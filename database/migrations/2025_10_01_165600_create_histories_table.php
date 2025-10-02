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
        Schema::create('histories', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('package_id')->constrained('packages', 'package_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('action_type', ['created', 'updated', 'status_changed', 'assigned', 'delivered', 'canceled', 'payment_received', 'dispute_opened', 'dispute_resolved']);
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['package_id', 'created_at']);
            $table->index(['user_id', 'action_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
