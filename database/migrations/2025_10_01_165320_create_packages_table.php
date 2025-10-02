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
        Schema::create('packages', function (Blueprint $table) {
            $table->id('package_id');
            $table->string('tracking_number', 20)->unique();
            $table->foreignId('sender_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('traveler_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 15);
            $table->string('recipient_email', 100)->nullable();
            $table->text('recipient_address');
            $table->text('package_description');
            $table->enum('category', ['documents', 'electronics', 'clothing', 'food', 'medicine', 'fragile', 'other'])->default('other');
            $table->decimal('weight', 5, 2);
            $table->decimal('dimensions_length', 5, 2)->nullable();
            $table->decimal('dimensions_width', 5, 2)->nullable();
            $table->decimal('dimensions_height', 5, 2)->nullable();
            $table->decimal('declared_value', 12, 2)->default(0.00);
            $table->decimal('insurance_value', 12, 2)->default(0.00);
            $table->boolean('fragile')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->text('pickup_address');
            $table->text('delivery_address');
            $table->string('pickup_city', 50);
            $table->string('delivery_city', 50);
            $table->decimal('estimated_delivery_fee', 8, 2)->nullable();
            $table->decimal('final_delivery_fee', 8, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'picked_up', 'in_transit', 'arrived', 'out_for_delivery', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->enum('priority', ['standard', 'express', 'urgent'])->default('standard');
            $table->enum('delivery_type', ['urban', 'intercity', 'international'])->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivery_photo')->nullable();
            $table->string('delivery_signature')->nullable();
            $table->string('pickup_photo')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('tracking_number');
            $table->index('sender_id');
            $table->index('traveler_id');
            $table->index('status');
            $table->index('pickup_city');
            $table->index('delivery_city');
            $table->index(['pickup_city', 'delivery_city']);
            $table->index('created_at');
            $table->index('priority');
            $table->index('category');
            $table->index('delivery_type');
        });
        
        // Add check constraints
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_weight CHECK (weight > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
