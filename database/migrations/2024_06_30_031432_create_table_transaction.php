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
        Schema::create('transactions', function (Blueprint $table) {
            //
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('tourism_id')->constrained()->on('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->on('users')->onDelete('cascade');
            $table->foreignId('product_id')->references('id')->on("product")->onDelete('cascade');
            $table->float('price');
            $table->integer('quantity');
            $table->float('total');
            // available status (draft, inprogress, paid, cancel, refund)
            $table->string('status')->nullable()->default('draft');
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};