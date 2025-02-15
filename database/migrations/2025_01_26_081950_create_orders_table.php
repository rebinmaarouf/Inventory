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
            $table->unsignedBigInteger('supplier_id'); // Changed from customer_id to supplier_id
            $table->string('payment_method'); // e.g., cash, card, bank
            $table->decimal('sub_total', 10, 2); // Subtotal amount
            $table->decimal('vat', 10, 2); // VAT amount
            $table->decimal('total', 10, 2); // Total amount
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade'); // Changed to suppliers
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
