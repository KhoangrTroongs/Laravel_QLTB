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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('available')->default(1)->after('status');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('available')->default(1)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('available');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('available');
        });
    }
};
