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
        Schema::table('travelers', function (Blueprint $table) {
            // Add vehicle-related fields
            $table->string('vehicle_type', 100)->after('user_id');
            $table->string('vehicle_model', 255)->after('vehicle_type');
            $table->integer('vehicle_year')->after('vehicle_model');
            $table->string('license_plate', 20)->after('vehicle_year');
            $table->string('driver_license', 50)->after('license_plate');
            $table->decimal('max_weight_kg', 8, 2)->after('driver_license');
            $table->string('max_dimensions', 255)->nullable()->after('max_weight_kg');
            $table->json('service_areas')->after('max_dimensions');
            $table->decimal('hourly_rate', 8, 2)->after('service_areas');
            $table->text('bio')->nullable()->after('hourly_rate');
            $table->boolean('is_verified')->default(false)->after('bio');
            $table->boolean('is_available')->default(true)->after('is_verified');
            $table->decimal('rating', 3, 2)->default(0)->after('is_available');
            $table->integer('total_deliveries')->default(0)->after('rating');
            
            // Add indexes
            $table->index('vehicle_type');
            $table->index('license_plate');
            $table->index('is_verified');
            $table->index('is_available');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travelers', function (Blueprint $table) {
            $table->dropIndex(['vehicle_type']);
            // $table->dropIndex(['license_plate']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['is_available']);
            $table->dropIndex(['rating']);
            
            $table->dropColumn([
                'vehicle_type',
                'vehicle_model', 
                'vehicle_year',
                'license_plate',
                'driver_license',
                'max_weight_kg',
                'max_dimensions',
                'service_areas',
                'hourly_rate',
                'bio',
                'is_verified',
                'is_available',
                'rating',
                'total_deliveries'
            ]);
        });
    }
};