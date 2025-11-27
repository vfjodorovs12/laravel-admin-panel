<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Добавление поля is_admin для управления правами администратора
     * 
     * Миграция добавляет поле is_admin (boolean) в таблицу users
     * для определения, имеет ли пользователь права администратора
     * 
     * @author ehosting.lv
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавляем поле is_admin после поля email
            // По умолчанию false - обычный пользователь
            $table->boolean('is_admin')->default(false)->after('email');
        });
    }

    /**
     * Откат миграции - удаление поля is_admin
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
