<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запустить миграции
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // create_news, edit_users
            $table->string('display_name')->nullable(); // Создание новостей
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Откатить миграции
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
