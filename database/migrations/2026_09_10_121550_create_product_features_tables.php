<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. レビューインセンティブ用のポイントカラムを users に追加
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('email');
        });

        // 2. 入力クーポン管理テーブル
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('discount_amount')->default(0); // 値引き額
            $table->integer('discount_rate')->default(0);   // 値引き率(%)
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });

        // 3. 注文テーブルにキャンセル・クーポン情報を追加
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('discount_amount')->default(0)->after('total_price');
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete()->after('discount_amount');
        });

        // 4. 再入荷リクエスト（入荷通知）テーブル
        Schema::create('restock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });

        // 5. 閲覧履歴テーブル
        Schema::create('view_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('view_histories');
        Schema::dropIfExists('restock_requests');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['discount_amount', 'coupon_id']);
        });
        Schema::dropIfExists('coupons');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};