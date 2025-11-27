<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MorreUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'email' => 'morre@ehosting.lv',
        ], [
            'name' => 'morre',
            'email' => 'morre@ehosting.lv',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
