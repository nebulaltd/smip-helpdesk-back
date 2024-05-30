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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('event_id');
            $table->integer('females');
            $table->integer('males');
            $table->text('notes')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->text('canceled_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
