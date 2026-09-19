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
        Schema::create('member_sessions', function (Blueprint $table) {
            $table->id('session_id');
            $table->foreignId('qr_access_id')->constrained('qr_accesses', 'qr_access_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members', 'member_id')->cascadeOnUpdate()->nullOnDelete();
            $table->string('session_token_hash', 255)->unique();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('last_activity_at')->nullable();
            $table->boolean('is_revoked')->default(false);

            $table->index(['session_token_hash', 'expires_at', 'is_revoked'], 'member_sessions_token_exp_rev_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_sessions');
    }
};
