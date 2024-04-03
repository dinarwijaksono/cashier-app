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
        Schema::create('stock_by_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('period', 20);
            $table->bigInteger('period_by_date');
            $table->boolean('is_closed');
            $table->decimal('first_stock', 14, 2);
            $table->decimal('adjusment', 14, 2);
            $table->decimal('last_stock', 14, 2);
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_by_periods');
    }
};
