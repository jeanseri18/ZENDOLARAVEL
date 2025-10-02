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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->string('conversation_id')->nullable();
            $table->foreignId('sender_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('packages', 'package_id')->onDelete('cascade');
            $table->enum('message_type', ['text', 'image', 'file', 'location', 'system']);
            $table->text('message_content');
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->bigInteger('attachment_size')->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('reply_to_message_id')->nullable()->constrained('messages', 'message_id')->onDelete('set null');
            $table->boolean('is_system_message')->default(false);
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_reported')->default(false);
            $table->timestamps();
            
            $table->index(['conversation_id']);
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['package_id']);
            $table->index(['sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
