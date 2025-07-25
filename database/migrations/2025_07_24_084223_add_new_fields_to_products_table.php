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
        Schema::table('products', function (Blueprint $table) {
            // Add low stock threshold column
            $table->integer('low_stock_threshold')->default(10)->after('quantity');
            
            // Add brand and model columns
            $table->string('brand')->nullable()->after('low_stock_threshold');
            $table->string('model')->nullable()->after('brand');
            
            // Add weight and dimensions columns
            $table->decimal('weight', 8, 2)->nullable()->after('model');
            $table->string('dimensions')->nullable()->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the added columns in reverse order
            $table->dropColumn([
                'dimensions',
                'weight', 
                'model',
                'brand',
                'low_stock_threshold'
            ]);
        });
    }
};
