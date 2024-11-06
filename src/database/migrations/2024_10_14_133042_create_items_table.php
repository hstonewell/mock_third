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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('brand_id')->constrained()->nullable();
            $table->foreignId('category_id')->constrained()->nullable();
            $table->foreignId('condition_id')->constrained()->nullable();
            $table->string('item_name');
            $table->unsignedInteger('price');
            $table->string('description')->nullable();
            $table->string('image');
            $table->boolean('sold_out')->default(false)->comment('0:販売中 1:売切');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
