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
        Schema::create('proposed_trips', function (Blueprint $table) {
            $table->id('trip_id');
            $table->foreignId('traveler_id')->constrained('travelers', 'traveler_id')->onDelete('cascade');
            $table->string('origin_city');
            $table->string('destination_city');
            $table->date('trip_date');
            $table->datetime('estimated_time')->nullable();
            $table->enum('vehicle_type', ['car', 'motorcycle', 'bicycle', 'truck', 'van', 'bus', 'train', 'plane', 'boat']);
            $table->integer('max_packages')->default(1);
            $table->decimal('max_weight_kg', 8, 2)->default(0.00);
            $table->json('accepted_package_types')->nullable();
            $table->boolean('requires_secure_payment')->default(false);
            $table->boolean('express_delivery')->default(false);
            $table->string('vehicle_photo')->nullable();
            $table->enum('status', ['active', 'completed', 'canceled', 'expired'])->default('active');
            $table->timestamps();
            
            $table->index(['traveler_id', 'status']);
            $table->index(['origin_city', 'destination_city']);
            $table->index('trip_date');
            $table->index('vehicle_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposed_trips');
    }
};
