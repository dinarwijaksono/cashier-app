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
        Schema::create('detailed_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreignId('receipt_id');
            $table->float('qty', 12.2);
            $table->float('price');
            $table->float('total');
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_receipts');
    }
};
