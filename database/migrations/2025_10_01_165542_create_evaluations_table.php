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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->foreignId('evaluator_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('evaluated_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages', 'package_id')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions', 'transaction_id')->onDelete('cascade');
            $table->decimal('overall_rating', 2, 1)->check('overall_rating >= 1 AND overall_rating <= 5');
            $table->tinyInteger('communication_rating')->check('communication_rating >= 1 AND communication_rating <= 5');
            $table->tinyInteger('punctuality_rating')->check('punctuality_rating >= 1 AND punctuality_rating <= 5');
            $table->tinyInteger('care_rating')->check('care_rating >= 1 AND care_rating <= 5');
            $table->tinyInteger('professionalism_rating')->check('professionalism_rating >= 1 AND professionalism_rating <= 5');
            $table->text('comment')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();
            $table->timestamps();
            
            $table->index(['evaluator_id', 'evaluated_id']);
            $table->index(['package_id', 'transaction_id']);
            $table->index('overall_rating');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
