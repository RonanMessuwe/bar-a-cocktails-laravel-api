<?php

use App\Enums\OrderStatus;
use App\Models\Order;
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
            
            $table->ulid('order_id')->primary();
            $table->enum('status', array_column(OrderStatus::cases(), 'value'))->default(OrderStatus::Pending);
            $table->timestamps();
            $table->enum('table', Order::$tables);
            // $table->foreignUlid('table_id')->constrained('tables', 'table_id');
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
