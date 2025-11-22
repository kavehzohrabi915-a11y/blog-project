<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/..._create_comments_table.php

public function up(): void
{
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        
        // اتصال به پست مربوطه
        $table->foreignId('post_id')->constrained()->onDelete('cascade');
        
        // اتصال به کاربر (اگر لاگین کرده باشد، اختیاری)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 

        $table->text('content');
        $table->string('author_name')->nullable(); // اگر کاربر مهمان بود
        $table->string('author_email')->nullable(); // اگر کاربر مهمان بود
        $table->boolean('is_approved')->default(false); // وضعیت تأیید
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
