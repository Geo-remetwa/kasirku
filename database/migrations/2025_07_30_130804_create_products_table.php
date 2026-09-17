<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('selling_price');
            $table->unsignedBigInteger('purchase_price');
            $table->unsignedInteger('stock');
            $table->foreignId('category_id')->constrained('product_categories');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
