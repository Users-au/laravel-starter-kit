<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('usersau_issuer')->nullable();
            $table->json('usersau_profile')->nullable();
            $table->json('interface_preferences')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['usersau_issuer', 'usersau_profile', 'interface_preferences']));
    }
};
