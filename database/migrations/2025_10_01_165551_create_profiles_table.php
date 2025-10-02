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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('badge', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->integer('total_packages_sent')->default(0);
            $table->integer('successful_deliveries')->default(0);
            $table->integer('canceled_packages')->default(0);
            $table->decimal('reliability_percentage', 5, 2)->default(0.00);
            $table->date('joined_date')->nullable();
            $table->string('activity_zone', 50)->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('badge');
            $table->index('reliability_percentage');
            $table->index('activity_zone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
