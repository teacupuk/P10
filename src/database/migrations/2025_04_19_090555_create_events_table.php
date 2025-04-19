<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_sprint')->default(false);

            // Foreign keys to drivers (nullable)
            $table->foreignId('p1_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p2_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p3_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p4_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p5_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p6_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p7_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p8_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p9_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('p10_driver_id')->nullable()->constrained('drivers')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
