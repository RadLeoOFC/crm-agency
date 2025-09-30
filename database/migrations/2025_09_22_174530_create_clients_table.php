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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Основная информация
            $table->string('name');                  // Название компании или ФИО
            $table->string('contact_person')->nullable(); // Контактное лицо
            $table->string('email')->unique();       // Email для связи/логина
            $table->string('phone')->nullable();     // Телефон
            $table->string('company')->nullable();   // Компания, если нужно отличать от name

            // Реквизиты и доп. данные
            $table->string('vat_number')->nullable(); // ИНН/НДС-номер
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address')->nullable();

            // Технические поля
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['email','is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
