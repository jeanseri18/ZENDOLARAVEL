<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('phone_number', 15)->unique();
            $table->string('email', 100)->unique()->nullable();
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('country', 50)->default('Côte d\'Ivoire');
            $table->text('bio')->nullable();
            $table->decimal('reliability_score', 3, 2)->default(0.00);
            $table->enum('role', ['expeditor', 'traveler', 'both']);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('two_factor_auth')->default(false);
            $table->timestamp('last_active')->default(now());
            $table->decimal('wallet_balance', 12, 2)->default(0.00);
            $table->timestamps();
            
            // Indexes
            $table->index('phone_number');
            $table->index('email');
            $table->index('role');
            $table->index('city');
            $table->index('is_verified');
            $table->index('is_active');
            $table->index('last_active');
            
            // Check constraints (will be added via raw SQL)
        });
        
        // Add check constraints
        DB::statement('ALTER TABLE users ADD CONSTRAINT chk_reliability_score CHECK (reliability_score >= 0 AND reliability_score <= 5.00)');
        DB::statement('ALTER TABLE users ADD CONSTRAINT chk_wallet_balance CHECK (wallet_balance >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
