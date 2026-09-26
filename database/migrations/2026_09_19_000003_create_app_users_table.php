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
        Schema::create('app_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('role_id')->constrained('roles', 'role_id')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('member_id')->nullable()->index();
            $table->string('username', 100)->unique();
            $table->string('password_hash', 255);
            $table->string('full_name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false)->index();
            $table->rememberToken();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->dateTime('date_created')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable()->index();
            $table->dateTime('date_modified')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->dateTime('date_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_users');
    }
};
