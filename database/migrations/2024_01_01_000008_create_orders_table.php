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
            $table->string('order_number')->unique();
            $table->enum('type', ['warehouse_entry', 'warehouse_to_sales_point', 'sales_point_sale', 'sales_point_return']);
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->nullable()->constrained('users');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('sales_point_id')->nullable()->constrained('sales_points');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
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