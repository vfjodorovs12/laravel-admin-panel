<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder для создания первого администратора
 * 
 * Создает тестового администратора с правами доступа к админ-панели
 * Email: admin@ehosting.lv
 * Пароль: password
 * 
 * ВАЖНО: После развертывания измените пароль администратора!
 * 
 * @author ehosting.lv
 * @copyright 2025
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Запуск seeder'а
     *
     * @return void
     */
    public function run(): void
    {
        // Проверяем, существует ли уже администратор
        $adminExists = User::where('email', 'admin@ehosting.lv')->exists();
        
        if ($adminExists) {
            $this->command->info('Администратор уже существует.');
            return;
        }
        
        // Создаем администратора
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@ehosting.lv',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('✓ Администратор успешно создан!');
        $this->command->info('Email: admin@ehosting.lv');
        $this->command->info('Пароль: password');
        $this->command->warn('ВАЖНО: Измените пароль после первого входа!');
        
        // Создаем несколько тестовых пользователей
        $this->command->info('Создание тестовых пользователей...');
        
        $testUsers = [
            [
                'name' => 'Иван Петров',
                'email' => 'ivan@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Мария Сидорова',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Алексей Иванов',
                'email' => 'alex@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
        ];
        
        foreach ($testUsers as $userData) {
            User::create($userData);
            $this->command->info("✓ Создан пользователь: {$userData['email']}");
        }
        
        $this->command->info('Все пользователи успешно созданы!');
    }
}
