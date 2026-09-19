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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('user_id')->constrained('app_users', 'user_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('notification_type', 50);
            $table->string('title', 255);
            $table->text('message');
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_deleted')->default(false)->index();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['user_id', 'read_at', 'date_created']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
