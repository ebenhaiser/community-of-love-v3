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
        Schema::create('event_cools', function (Blueprint $table) {
            $table->id('event_cool_id');
            $table->foreignId('event_id')->constrained('church_events', 'event_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('cool_id')->constrained('cools', 'cool_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['event_id', 'cool_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_cools');
    }
};
