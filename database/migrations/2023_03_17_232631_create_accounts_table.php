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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->decimal('opening_balance', 13, 2);
            $table->decimal('balance', 13, 2);
            $table->foreignId('bank_id')->constrained()->nullable();
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
