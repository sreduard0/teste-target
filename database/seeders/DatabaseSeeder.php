<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crie um usuário Admin e 5 usuários comuns com 2 endereços cada.
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'cpf' => '11122233344',
            'role' => 'admin',
        ]);

        \App\Models\User::factory(5)->create()->each(function ($user) {
            \App\Models\Address::factory(2)->create(['user_id' => $user->id]);
        });
    }
}
