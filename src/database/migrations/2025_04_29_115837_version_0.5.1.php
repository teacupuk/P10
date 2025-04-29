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
        Schema::table('drivers', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('nationality');
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color', 7)->nullable(); // e.g. “#FF0000”
            $table->timestamps();
        });

        Schema::table('drivers', function (Blueprint $table) {
            // Add the FK column
            $table->unsignedBigInteger('team_id')->nullable()->after('id');

            // If you still have an old 'team' varchar column, drop it:
            $table->dropColumn('team');

            // Then define the foreign key:
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Remove the FK constraint, then the column
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');

            // If you want to restore the old 'team' column:
            $table->string('team')->after('name');
        });
        
        Schema::dropIfExists('teams');
    }
};
