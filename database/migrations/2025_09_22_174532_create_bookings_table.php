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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();

            // Либо через слоты, либо через даты/время
            $table->foreignId('slot_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Финансовые данные
            $table->decimal('price', 10, 2); // Итоговая цена (final_price)
            // сюда потом добавляются list_price, discount_amount, currency, promo_code_id отдельной миграцией

            // Статус бронирования
            $table->enum('status', [
                'pending',   // создано, ждёт подтверждения
                'confirmed', // подтверждено
                'cancelled', // отменено
                'completed', // выполнено
            ])->default('pending');

            // Служебные
            $table->text('notes')->nullable();
            $table->timestamps();

            // Индексы
            $table->index(['platform_id', 'status']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
