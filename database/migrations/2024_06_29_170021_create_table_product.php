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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('description_details');
            $table->foreignId('users_id')->constrained()->on('users')->onDelete('cascade');
            $table->float('price');
            $table->integer('min_order');
            $table->string('duration');
            $table->string('location');
            $table->boolean('is_event')->default(false);
            $table->boolean('is_package')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};