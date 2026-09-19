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
        Schema::create('shepherds', function (Blueprint $table) {
            $table->id('shepherd_id');
            $table->string('name', 255);
            $table->string('phone', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false)->index();
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
        Schema::dropIfExists('shepherds');
    }
};
