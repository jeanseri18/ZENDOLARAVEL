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
        Schema::create('travelers', function (Blueprint $table) {
            $table->id('traveler_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('departure_city', 50);
            $table->string('arrival_city', 50);
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->decimal('available_weight', 5, 2);
            $table->decimal('max_package_weight', 5, 2)->default(10.00);
            $table->decimal('price_per_kg', 8, 2);
            $table->enum('transport_type', ['avion', 'bus', 'voiture', 'train'])->default('avion');
            $table->enum('status', ['active', 'in_progress', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen')->default(now());
            $table->text('special_instructions')->nullable();
            $table->boolean('accepts_fragile')->default(true);
            $table->boolean('accepts_documents')->default(true);
            $table->boolean('verification_required')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('departure_city');
            $table->index('arrival_city');
            $table->index('departure_date');
            $table->index('status');
            $table->index(['departure_city', 'arrival_city']);
            $table->index(['departure_date', 'arrival_date']);
            $table->index('is_online');
            $table->index('transport_type');
        });
        
        // Add check constraints
        DB::statement('ALTER TABLE travelers ADD CONSTRAINT chk_available_weight CHECK (available_weight > 0)');
        DB::statement('ALTER TABLE travelers ADD CONSTRAINT chk_price_per_kg CHECK (price_per_kg > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
