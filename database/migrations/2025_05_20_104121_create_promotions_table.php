<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['discount', 'buy_get_free']);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_value', 8, 2)->nullable();
            $table->foreignId('free_item_id')->nullable()->constrained('products')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->morphs('promotable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotable');
        Schema::dropIfExists('promotions');
    }
};