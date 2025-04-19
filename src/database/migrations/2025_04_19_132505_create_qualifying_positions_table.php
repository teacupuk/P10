<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qualifying_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('position'); // 1 to 20
            $table->timestamps();

            $table->unique(['event_id', 'position']);
            $table->unique(['event_id', 'driver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualifying_positions');
    }
};
