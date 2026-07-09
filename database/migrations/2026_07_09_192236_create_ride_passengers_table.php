<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained()->cascadeOnDelete();
            $table->string('sender_jid');
            $table->string('sender_name')->nullable();
            $table->unsignedTinyInteger('seats')->default(1);
            $table->timestamps();

            $table->unique(['ride_id', 'sender_jid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_passengers');
    }
};
