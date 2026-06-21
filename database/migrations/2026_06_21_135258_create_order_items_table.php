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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('machine_slot_id')->constrained()->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->boolean('is_dispensed')->default(false);
            $table->timestamp('dispense_attempted_at')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
