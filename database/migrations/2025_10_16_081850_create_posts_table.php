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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // شناسه یکتا برای هر پست

            // کلید خارجی: اتصال به جدول users (نویسنده پست)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('شناسه کاربر نویسنده پست');

            $table->string('title', 100)->comment('عنوان پست با حداکثر ۱۰۰ کاراکتر'); // محدود کردن طول عنوان
            $table->string('slug')->unique()->comment('آدرس یکتا و تمیز برای پست'); // آدرس یکتا
            $table->text('content')->comment('محتوای اصلی پست'); // محتوای کامل پست
            $table->string('thumbnail')->nullable()->comment('مسیر تصویر شاخص (اختیاری)'); // تصویر شاخص

            // وضعیت انتشار: برای مدیریت زمان انتشار یا پیش‌نویس
            $table->timestamp('published_at')
                  ->nullable()
                  ->index()
                  ->comment('زمان انتشار پست (null برای پیش‌نویس)');

            $table->timestamps(); // ستون‌های created_at و updated_at

            // افزودن ایندکس برای بهینه‌سازی کوئری‌ها
            $table->index('user_id', 'posts_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts'); // حذف جدول در صورت بازگشت مهاجرت
    }
};