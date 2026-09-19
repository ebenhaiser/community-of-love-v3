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
        Schema::create('activity_materials', function (Blueprint $table) {
            $table->id('material_id');
            $table->foreignId('activity_id')->constrained('activities', 'activity_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('material_type', 50);
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->string('external_url', 1000)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('description', 255)->nullable();
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_uploaded')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['activity_id', 'is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_materials');
    }
};
