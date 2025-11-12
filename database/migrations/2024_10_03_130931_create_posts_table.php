<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('community_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('title');
        $table->string('slug')->unique()->nullable();
        $table->text('content');
        $table->string('image_url')->nullable();
        $table->boolean('is_pinned')->default(false); // A coluna está aqui, que é o lugar certo
        $table->timestamps();
    });
} 

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};