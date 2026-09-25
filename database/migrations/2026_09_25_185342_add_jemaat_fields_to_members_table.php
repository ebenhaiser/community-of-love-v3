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
        Schema::table('members', function (Blueprint $table) {
            if (! Schema::hasColumn('members', 'gender')) {
                $table->string('gender', 20)->nullable()->after('name');
            }
            if (! Schema::hasColumn('members', 'birthplace')) {
                $table->string('birthplace', 100)->nullable()->after('gender');
            }
            if (! Schema::hasColumn('members', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('birthplace');
            }
            if (! Schema::hasColumn('members', 'address')) {
                $table->text('address')->nullable()->after('birthdate');
            }
            if (! Schema::hasColumn('members', 'social_media')) {
                $table->string('social_media', 255)->nullable()->after('address');
            }
            if (! Schema::hasColumn('members', 'kom_status')) {
                $table->string('kom_status', 50)->default('Belum KOM')->after('social_media');
            }
            if (! Schema::hasColumn('members', 'marital_status')) {
                $table->string('marital_status', 50)->default('Belum Menikah')->after('kom_status');
            }
            if (! Schema::hasColumn('members', 'is_in_cool')) {
                $table->boolean('is_in_cool')->default(false)->index()->after('marital_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $colsToDrop = [];
            foreach (['gender', 'birthplace', 'birthdate', 'address', 'social_media', 'kom_status', 'marital_status', 'is_in_cool'] as $col) {
                if (Schema::hasColumn('members', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (! empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
