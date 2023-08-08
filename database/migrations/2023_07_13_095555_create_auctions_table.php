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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artwork_id');
            $table->foreign('artwork_id')
                ->references('id')
                ->on('artworks');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')
                ->references('id')
                ->on('clients');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->foreign('winner_id')
                ->references('id')
                ->on('clients');
            $table->string('name');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->double('start_bid');
            $table->double('current_bid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
