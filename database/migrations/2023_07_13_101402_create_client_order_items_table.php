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
        Schema::create('client_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('client_order_id');
            $table->foreign('client_order_id')
                ->references('id')
                ->on('client_orders');
            $table->unsignedBigInteger('artwork_id');
            $table->foreign('artwork_id')
                ->references('id')
                ->on('artworks');
            $table->primary(['client_order_id', 'artwork_id']);
            $table->integer('quantity');
            $table->double('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_order_items');
    }
};
