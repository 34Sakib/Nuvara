<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Trust Features
        if (!Schema::hasTable('trust_features')) {
            Schema::create('trust_features', function (Blueprint $table) {
                $table->id();
                $table->string('feature_key')->unique();
                $table->json('title');
                $table->json('sub');
                $table->string('icon')->default('Truck');
                $table->string('icon_color')->default('text-emerald-500');
                $table->string('bg_color')->default('bg-emerald-500/10');
                $table->integer('sort_order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 2. Flash Sales Campaign
        if (!Schema::hasTable('flash_sales')) {
            Schema::create('flash_sales', function (Blueprint $table) {
                $table->id();
                $table->json('title');
                $table->dateTime('ends_at');
                $table->string('discount_label')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 3. Testimonials
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('rating')->default(5);
                $table->json('quote');
                $table->string('avatar')->nullable();
                $table->boolean('is_featured')->default(true);
                $table->integer('sort_order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 4. Update Banners table columns
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'type')) {
                $table->string('type')->default('hero_slider')->after('id');
            }
            if (!Schema::hasColumn('banners', 'badge')) {
                $table->string('badge')->nullable()->after('title');
            }
            if (!Schema::hasColumn('banners', 'badge_text')) {
                $table->string('badge_text')->nullable()->after('badge');
            }
            if (!Schema::hasColumn('banners', 'headline')) {
                $table->json('headline')->nullable()->after('badge_text');
            }
            if (!Schema::hasColumn('banners', 'sub')) {
                $table->json('sub')->nullable()->after('headline');
            }
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->json('button_text')->nullable()->after('sub');
            }
            if (!Schema::hasColumn('banners', 'bg_gradient')) {
                $table->string('bg_gradient')->nullable()->after('button_text');
            }
            if (!Schema::hasColumn('banners', 'text_color')) {
                $table->string('text_color')->nullable()->after('bg_gradient');
            }
            if (!Schema::hasColumn('banners', 'badge_bg')) {
                $table->string('badge_bg')->nullable()->after('text_color');
            }
            if (!Schema::hasColumn('banners', 'btn_style')) {
                $table->string('btn_style')->nullable()->after('badge_bg');
            }
            if (!Schema::hasColumn('banners', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('link')->constrained('products')->onDelete('set null');
            }
            if (!Schema::hasColumn('banners', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('product_id');
            }
            if (!Schema::hasColumn('banners', 'status')) {
                $table->boolean('status')->default(true)->after('sort_order');
            }
        });

        // 5. Update Products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_flash_deal')) {
                $table->boolean('is_flash_deal')->default(false)->after('is_new');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trust_features');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('testimonials');
    }
};
