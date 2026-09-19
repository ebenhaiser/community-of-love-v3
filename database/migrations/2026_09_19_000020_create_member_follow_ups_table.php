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
        Schema::create('member_follow_ups', function (Blueprint $table) {
            $table->id('follow_up_id');
            $table->foreignId('member_id')->constrained('members', 'member_id')->cascadeOnDelete();
            $table->foreignId('cool_id')->nullable()->constrained('cools', 'cool_id')->nullOnDelete();
            $table->string('status', 50)->default('PENDING')->index(); // PENDING, RESOLVED
            $table->integer('consecutive_absent_count')->default(2);
            $table->string('action_taken', 100)->nullable(); // WhatsApp / Telepon, Kunjungan Rumah, Konseling Pastoral, Doa Bersama, Lainnya
            $table->text('notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('handled_at')->nullable();
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
        Schema::dropIfExists('member_follow_ups');
    }
};
