<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/..._create_post_tag_table.php

public function up(): void
{
    Schema::create('post_tag', function (Blueprint $table) {
        // این جدول نیازی به 'id' ندارد (Pivot Table)
        
        // کلید خارجی برای posts
        $table->foreignId('post_id')->constrained()->onDelete('cascade');
        
        // کلید خارجی برای tags
        $table->foreignId('tag_id')->constrained()->onDelete('cascade');
        
        // تضمین می‌کند که هیچ ترکیب تکراری (پست-برچسب) وجود نداشته باشد
        $table->primary(['post_id', 'tag_id']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
