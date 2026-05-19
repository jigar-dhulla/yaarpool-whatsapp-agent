<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('chat_jid')->nullable();
            $table->string('sender_jid')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('from_location');
            $table->string('to_location');
            $table->string('when_text');
            $table->dateTime('departs_at');
            $table->unsignedTinyInteger('seats')->default(1);
            $table->string('price_per_seat')->nullable();
            $table->string('vehicle')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['chat_jid', 'created_at']);
            $table->index(['sender_jid', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
