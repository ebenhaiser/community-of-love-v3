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
        Schema::create('cools', function (Blueprint $table) {
            $table->id('cool_id');
            $table->string('cool_code', 50)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('shepherd_id')->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('modified_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_modified')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['shepherd_id', 'is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cools');
    }
};
