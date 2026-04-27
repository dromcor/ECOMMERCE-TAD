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
        // User::factory(10)->create();

        \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );

        // Call project seeders
        $this->call([
            \Database\Seeders\RolesSeeder::class,
            \Database\Seeders\ProductsSeeder::class,
            \Database\Seeders\TestOrderSeeder::class,
        ]);

        // Assign admin role to test user for convenience
        $user = \App\Models\User::where('email', 'test@example.com')->first();
        if ($user) {
            $roleId = \Illuminate\Support\Facades\DB::table('roles')->where('nombre', 'admin')->value('id');
            if ($roleId) {
                \Illuminate\Support\Facades\DB::table('role_user')->insertOrIgnore(['rol_id' => $roleId, 'usuario_id' => $user->id, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }
}
