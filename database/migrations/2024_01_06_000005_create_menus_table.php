<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->foreignId('cms_page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('icon')->nullable();
            $table->string('target', 10)->default('_self');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->enum('visibility', ['all','guests','authenticated','premium'])->default('all');
            $table->timestamps();
            $table->index(['menu_location_id', 'parent_id', 'sort_order']);
        });
    }
    public function down(): void { Schema::dropIfExists('menus'); }
};
