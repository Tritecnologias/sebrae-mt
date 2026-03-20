<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // O cast 'hashed' no Model User já aplica Hash::make automaticamente,
        // então passamos a senha em texto puro.
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrador',
                'password' => 'password',
            ]
        );
    }
}
