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
        // 1. Crear Estaciones
        $estaciones = [
            ['nombre' => 'Corte', 'orden' => 1],
            ['nombre' => 'Costura', 'orden' => 2],
            ['nombre' => 'Control de Calidad', 'orden' => 3],
        ];

        foreach ($estaciones as $estacion) {
            \App\Models\Estacion::create($estacion);
        }

        // 2. Crear Defectos
        $defectos = ['Costura suelta', 'Mancha de aceite', 'Agujero en tela', 'Medida incorrecta'];
        foreach ($defectos as $defecto) {
            \App\Models\Defecto::create(['nombre' => $defecto]);
        }

        // 3. Crear Usuarios por cada rol
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@textile.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@textile.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Operator User',
            'email' => 'operator@textile.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Quality User',
            'email' => 'quality@textile.com',
            'password' => bcrypt('password'),
            'role' => 'quality',
        ]);
    }
}
