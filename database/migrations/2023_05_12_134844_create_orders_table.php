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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->decimal('amount', 10, 2);
            $table->enum('status', [
                'поступил', 
                'водитель назначен', 
                'водитель на месте', 
                'исполняется', 
                'выполнен'
                ])->default('поступил');
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
