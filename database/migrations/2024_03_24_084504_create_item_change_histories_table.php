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
        Schema::create('item_change_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('before_name', 100);
            $table->string('before_unit', 10);
            $table->float('before_price', 12.2);
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_change_histories');
    }
};
