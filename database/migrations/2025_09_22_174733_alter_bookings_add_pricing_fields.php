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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings','list_price')) {
                $table->decimal('list_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('bookings','discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('list_price');
            }
            if (!Schema::hasColumn('bookings','currency')) {
                $table->string('currency', 3)->default('USD')->after('discount_amount');
            }
            if (!Schema::hasColumn('bookings','promo_code_id')) {
                $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete()->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings','promo_code_id')) { $table->dropConstrainedForeignId('promo_code_id'); }
            if (Schema::hasColumn('bookings','currency')) { $table->dropColumn('currency'); }
            if (Schema::hasColumn('bookings','discount_amount')) { $table->dropColumn('discount_amount'); }
            if (Schema::hasColumn('bookings','list_price')) { $table->dropColumn('list_price'); }
        });
    }
};
