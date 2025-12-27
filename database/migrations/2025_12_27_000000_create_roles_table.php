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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, moderator, user, editor
            $table->string('display_name')->nullable(); // Администратор
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Откатить миграции
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
