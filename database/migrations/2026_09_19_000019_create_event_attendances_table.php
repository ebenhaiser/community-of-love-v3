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
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id('event_attendance_id');
            $table->foreignId('event_id')->constrained('church_events', 'event_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members', 'member_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attendance_status_id')->constrained('attendance_statuses', 'attendance_status_id')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('attendance_time')->nullable();
            $table->string('note', 255)->nullable();
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('modified_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_modified')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['event_id', 'member_id', 'attendance_status_id'], 'event_attendances_event_mbr_stat_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
