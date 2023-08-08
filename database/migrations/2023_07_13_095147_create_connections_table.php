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
        Schema::create('connections', function (Blueprint $table) {
            $table->unsignedBigInteger('requester_id');
            $table->foreign('requester_id')
                ->references('id')
                ->on('clients');
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')
                ->references('id')
                ->on('clients');
            $table->primary(['requester_id', 'receiver_id']);
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
