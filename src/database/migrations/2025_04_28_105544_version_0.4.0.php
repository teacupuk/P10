<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Season;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Creates the Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Creates the Cache Table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Create Jobs Table
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Create Players Table
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        // Create the Drivers Table
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('team')->nullable();
            $table->string('nationality')->nullable();
            $table->timestamps();
        });

        // Creates the Seasons Table
        Schema::create('seasons', function (Blueprint $table) {
            $table->year('id')->primary();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        // Creates the current year as the first season
        Season::firstOrCreate(['id' => now()->year], ['active' => true]);

        // Creates the Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_code', 2)->nullable();
            $table->date('date');
            $table->year('season_id')->default(date('Y'));
            $table->boolean('is_sprint')->default(false);
            $table->boolean('double_points')->default(false);
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        // Add foreign key constraint to seasons
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons')
                  ->onDelete('cascade');
        });

        // Creates the Predictions Table
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('predicted_driver');
            $table->integer('points_awarded')->default(0);
            $table->timestamps();
        });

        // Creates the Qualifying Table
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drops Child Tables
        Schema::dropIfExists('qualifying_positions');
        Schema::dropIfExists('predictions');

        // Drops Foreign Key
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['season_id']);
        });

        // Drops Parent Tables
        Schema::dropIfExists('events');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('players');

        // Drops Jobs Tables
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');

        // Drops all remaining Tables
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('users');
    }
};
