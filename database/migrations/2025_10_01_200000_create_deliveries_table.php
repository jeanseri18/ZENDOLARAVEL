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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->unsignedBigInteger('package_id')->unique();
            $table->unsignedBigInteger('traveler_id')->nullable();
            $table->enum('delivery_type', ['urban', 'intercity', 'international']);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('pickup_location', 255)->nullable();
            $table->string('delivery_location', 255)->nullable();
            $table->string('actual_delivery_time', 10)->nullable();
            $table->text('customs_declaration')->nullable(); // Pour international uniquement
            $table->string('flight_ticket_path', 255)->nullable(); // Pour international uniquement
            $table->decimal('commission_fee', 10, 2)->default(0.00);
            $table->text('gps_tracking_data')->nullable(); // Données GPS en JSON
            $table->enum('status', ['to_pickup', 'in_transit', 'delivered', 'delayed', 'canceled'])->default('to_pickup');
            $table->timestamps();

            // Foreign keys
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
            $table->foreign('traveler_id')->references('traveler_id')->on('travelers')->onDelete('set null');

            // Indexes
            $table->index('package_id');
            $table->index('traveler_id');
            $table->index('delivery_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};