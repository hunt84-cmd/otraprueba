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
        Schema::create('sales_point_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_point_id')->constrained('sales_points');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->timestamps();
            
            $table->unique(['sales_point_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_point_inventory');
    }
};