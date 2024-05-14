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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('status_id')->constrained('event_statuses', 'id')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('image_id')->constrained('files', 'id')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('files', 'id')->onDelete('cascade');


            $table->timestamp('date_registration');
            $table->timestamp('date_start');
            $table->timestamp('date_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['image_id']);
            $table->dropForeign(['task_id']);
        });
        Schema::dropIfExists('events');
    }
};
