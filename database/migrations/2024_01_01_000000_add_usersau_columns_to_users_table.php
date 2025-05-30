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
            // Change password column to nullable
            $table->string('password')->nullable()->change();
            $table->string('usersau_id')->nullable()->after('email');
            $table->text('usersau_access_token')->nullable()->after('usersau_id');
            $table->text('usersau_refresh_token')->nullable()->after('usersau_access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['usersau_id', 'usersau_access_token', 'usersau_refresh_token']);
        });
    }
}; 