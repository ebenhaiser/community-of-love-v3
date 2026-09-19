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
        Schema::create('cool_members', function (Blueprint $table) {
            $table->id('cool_member_id');
            $table->foreignId('cool_id')->constrained('cools', 'cool_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members', 'member_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 50)->default('ACTIVE')->index();
            $table->boolean('is_deleted')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_created')->nullable();
            $table->foreignId('modified_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_modified')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('app_users', 'user_id')->nullOnDelete();
            $table->dateTime('date_deleted')->nullable();

            $table->unique(['cool_id', 'member_id', 'start_date']);
            $table->index(['cool_id', 'member_id', 'status', 'is_deleted'], 'cool_members_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cool_members');
    }
};
