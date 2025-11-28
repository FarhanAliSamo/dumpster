<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('container_prices', function (Blueprint $table) {
            $table->id();

            // No relation, plain integer
            $table->unsignedBigInteger('container_id');

            // Optional county
            $table->unsignedBigInteger('county_id')->nullable();

            // Optional zip code override
            $table->string('zip_code')->nullable();

            // Pricing
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('county_price', 10, 2)->nullable();
            $table->decimal('zip_price', 10, 2)->nullable();

            // Extra info
            $table->decimal('weight_limit', 10, 2)->nullable();
            $table->decimal('rental_price', 10, 2)->nullable();

            $table->text('special_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_prices');
    }
};


