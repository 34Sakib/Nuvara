<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('channel')->default('online');
            $table->string('payment_method')->nullable();
            $table->string('cashier_name')->nullable();
        });
    }
    public function down(): void {
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['channel', 'payment_method', 'cashier_name']));
    }
};
