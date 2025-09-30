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
        Schema::create('price_list_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday')->nullable(); // 1..7 (Mon..Sun), null=any
            $table->time('starts_at');
            $table->time('ends_at');
            $table->decimal('slot_price', 10, 2);
            $table->unsignedInteger('capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['price_list_id','weekday','is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_rules');
    }
};
