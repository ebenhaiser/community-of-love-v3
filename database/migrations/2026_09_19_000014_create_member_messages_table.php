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
        Schema::create('member_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->foreignId('cool_id')->constrained('cools', 'cool_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members', 'member_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('shepherd_id')->nullable()->constrained('shepherds', 'shepherd_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('member_session_id')->nullable()->constrained('member_sessions', 'session_id')->cascadeOnUpdate()->nullOnDelete();
            $table->text('message');
            $table->string('status', 50)->default('UNREAD');
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_deleted')->default(false)->index();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->index(['cool_id', 'shepherd_id', 'member_id', 'date_created', 'is_deleted'], 'member_msgs_cool_shep_mbr_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_messages');
    }
};
