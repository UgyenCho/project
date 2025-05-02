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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role'); 
            $table->string('user_type')->default(0);
            $table->foreignId('department_id')
                  ->nullable() // Make nullable if a user might not have a department
                  ->constrained('departments') // Assumes table is named 'departments'
                  ->onDelete('set null'); // Or 'cascade' if users should be deleted when dept is deleted
            // --- END ADDITION ---

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable(); // Kept as per original
            $table->string('profile_photo_path', 2048)->nullable(); // Kept as per original
            $table->timestamps();
        });

        // These other table definitions remain unchanged
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // ... (content remains the same)
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            // ... (content remains the same)
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of creation
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};