<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('unknown');
            $table->string('customer_email')->nullable();
            $table->uuid('checkout_key')->nullable()->unique();
            $table->string('checkout_hash', 64)->nullable();
            $table->json('checkout_receipt')->nullable();
            $table->string('coupon_code')->nullable();
        });
        Schema::table('coupons', fn (Blueprint $table) => $table->unsignedInteger('times_used')->default(0));
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['checkout_key']);
            $table->dropColumn(['payment_status', 'customer_email', 'checkout_key', 'checkout_hash', 'checkout_receipt', 'coupon_code']);
        });
        Schema::table('coupons', fn (Blueprint $table) => $table->dropColumn('times_used'));
    }
};
