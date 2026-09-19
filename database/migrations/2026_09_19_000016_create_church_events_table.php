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
        Schema::create('church_events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_code', 50)->unique();
            $table->string('name', 255);
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 50)->default('SCHEDULED');
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('modified_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_modified')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church_events');
    }
};
