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
        Schema::table('equipment_users', function (Blueprint $table) {
            $table->dateTime('ngaytra')->nullable()->after('ngaymuon');
            $table->dateTime('hantra')->nullable()->after('ngaymuon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_users', function (Blueprint $table) {
            $table->dropColumn(['ngaytra', 'hantra']);
        });
    }
};
