<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('cashier_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('transaction_at')->index();
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('paid_amount');
            $table->unsignedBigInteger('change_amount')->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'qris']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['cashier_id', 'transaction_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
