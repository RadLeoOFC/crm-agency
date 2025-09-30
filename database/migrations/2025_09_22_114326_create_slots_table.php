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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();

            // К какой площадке относится слот
            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();

            // Опционально: из какого прайс-листа/правила он родился
            $table->foreignId('price_list_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('price_list_rule_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('price_override_id')->nullable()->constrained()->nullOnDelete();

            // Время
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            // Цена именно для этого слота (фиксируем!)
            $table->decimal('price', 10, 2);

            // Статус слота
            $table->enum('status', [
                'available', // свободен
                'booked',    // занят
                'blocked',   // заблокирован админом
            ])->default('available');

            // Если capacity > 1, можно указать сколько ещё мест осталось
            $table->unsignedInteger('capacity')->default(1);
            $table->unsignedInteger('used_capacity')->default(0);

            $table->timestamps();

            // Индексы
            $table->index(['platform_id', 'starts_at']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
