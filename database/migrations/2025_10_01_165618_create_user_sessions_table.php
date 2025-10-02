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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->string('session_id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('device_type', ['desktop', 'mobile', 'tablet', 'unknown'])->default('unknown');
            $table->string('location_country')->nullable();
            $table->string('location_city')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index('last_activity');
            $table->index('expires_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
