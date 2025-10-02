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
        Schema::create('identity_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('document_type', ['passport', 'national_id', 'driver_license', 'residence_permit']);
            $table->string('document_number', 50);
            $table->string('document_photo');
            $table->string('document_photo_back')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_country', 50)->default('Côte d\'Ivoire');
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('uploaded_at')->default(now());
            $table->timestamp('verified_at')->nullable();
            $table->integer('verified_by')->nullable();
            $table->boolean('is_primary')->default(false);
            
            // Indexes
            $table->index('user_id');
            $table->index('document_type');
            $table->index('verification_status');
            $table->index('expiry_date');
            $table->unique(['user_id', 'document_type', 'document_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_documents');
    }
};
