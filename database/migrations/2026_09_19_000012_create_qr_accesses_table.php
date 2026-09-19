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
        Schema::create('qr_accesses', function (Blueprint $table) {
            $table->id('qr_access_id');
            $table->foreignId('cool_id')->constrained('cools', 'cool_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('access_code', 100)->nullable();
            $table->string('pin_hash', 255);
            $table->string('qr_token', 255)->unique();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('last_used_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('modified_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_modified')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['cool_id', 'qr_token', 'is_active', 'is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_accesses');
    }
};
