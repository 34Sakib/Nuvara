<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('register_sessions', function (Blueprint $table) {
            $table->id(); $table->string('register_code'); $table->string('cashier_name');
            $table->decimal('opening_float', 10, 2); $table->decimal('closing_cash', 10, 2)->nullable();
            $table->decimal('expected_cash', 10, 2)->nullable(); $table->decimal('variance', 10, 2)->nullable();
            $table->string('status')->default('open'); $table->timestamp('opened_at'); $table->timestamp('closed_at')->nullable(); $table->timestamps();
        });
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id(); $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('quantity'); $table->string('reason'); $table->string('reference')->nullable(); $table->string('actor')->nullable(); $table->timestamps();
        });
        Schema::create('refunds', function (Blueprint $table) {
            $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('refund_number')->unique(); $table->decimal('amount', 10, 2); $table->string('reason');
            $table->string('restock_status')->default('restocked'); $table->string('actor')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('refunds'); Schema::dropIfExists('inventory_movements'); Schema::dropIfExists('register_sessions'); }
};
