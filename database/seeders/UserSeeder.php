<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(1)->create([
            'name' => 'Mihai Badea',
            'email' => 'demo@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Alice',
            'email' => 'alice@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Bogdan',
            'email' => 'bogdan@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Mikele',
            'email' => 'mikele@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Catalin',
            'email' => 'catalin@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Larisa',
            'email' => 'larisa@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Mihai',
            'email' => 'mihai@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Radu',
            'email' => 'radu@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Cristi',
            'email' => 'cristi@dinero.app'
        ]);

        User::factory()->count(1)->create([
            'name' => 'Victor',
            'email' => 'victor@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Madalin',
            'email' => 'madalin@dinero.app'
        ]);
        User::factory()->count(1)->create([
            'name' => 'Cosmin',
            'email' => 'cosmin@dinero.app'
        ]);
    }
}
