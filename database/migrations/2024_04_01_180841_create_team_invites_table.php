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
        Schema::create('team_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('to_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('from_user')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_invites', function (Blueprint $table) {

            $table->dropForeign(['team_id']);
            $table->dropForeign(['to_user']);
            $table->dropForeign(['from_user']);
        });

        Schema::dropIfExists('team_invites');
    }
};
