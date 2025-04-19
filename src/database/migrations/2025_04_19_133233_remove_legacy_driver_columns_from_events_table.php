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
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['p1_driver_id']);
            $table->dropForeign(['p2_driver_id']);
            $table->dropForeign(['p3_driver_id']);
            $table->dropForeign(['p4_driver_id']);
            $table->dropForeign(['p5_driver_id']);
            $table->dropForeign(['p6_driver_id']);
            $table->dropForeign(['p7_driver_id']);
            $table->dropForeign(['p8_driver_id']);
            $table->dropForeign(['p9_driver_id']);
            $table->dropForeign(['p10_driver_id']);
        
            $table->dropColumn([
                'p1_driver_id', 'p2_driver_id', 'p3_driver_id', 'p4_driver_id', 'p5_driver_id',
                'p6_driver_id', 'p7_driver_id', 'p8_driver_id', 'p9_driver_id', 'p10_driver_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
