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
            // simpan data kontak ke 2.
            $table->string('phone_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->float('price');
            $table->integer('quantity');
            $table->float('total');
            $table->string('order_data')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->string('refund_reason')->nullable();
            $table->string('customer_note')->nullable();
            $table->string('admin_note')->nullable();
            // payment yang akan di handle oleh midtrans melalui
            // webhook
            $table->string('payment_id')->unique()->nullable();
            $table->timestamp('payment_time')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_code')->nullable();
            // status tersedia (draft, inprogress, paid, cancel, refund, done)
            // draft : status produk yang masih di dalam wishlist atau cart
            // inprogress : status produk yang menunggu di bayar
            // paid : status produk yang sudah di bayar
            // cancel : status produk yang dibatalkan
            // refund : status produk yang di refund
            // done : status produk yang selesai (sudah liburan).
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
