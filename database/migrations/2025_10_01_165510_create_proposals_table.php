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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id('proposal_id');
            $table->foreignId('package_id')->constrained('packages', 'package_id')->onDelete('cascade');
            $table->foreignId('traveler_id')->constrained('travelers', 'traveler_id')->onDelete('cascade');
            $table->decimal('proposed_price', 10, 2);
            $table->decimal('original_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->date('estimated_pickup_date');
            $table->date('estimated_delivery_date');
            $table->text('message')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['package_id', 'status']);
            $table->index(['traveler_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
