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
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->enum('type', ['telegram', 'youtube', 'facebook', 'website']);
            $table->text('description')->nullable(); // теперь необязательное
            $table->char('currency', 3)->default('USD'); // базовая валюта для прайсов
            $table->string('timezone', 64)->default('Europe/Sofia'); // локальная TZ
            $table->boolean('is_active')->default(true); // активна ли площадка
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
