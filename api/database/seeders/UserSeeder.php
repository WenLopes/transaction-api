<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert([
            'name' => 'Wendel Lopes',
            'email' => 'wendel@mail.com.br',
            'document' => '88853104007',
            'is_seller' => false,
            'password' => bcrypt('pass123'),
            'balance' => 2000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('users')->insert([
            'name' => 'Vestimentas torres',
            'email' => 'vestimentas@torres.com.br',
            'document' => '34028650000167',
            'is_seller' => true,
            'password' => bcrypt('pass123'),
            'balance' => 10000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \App\Models\User\User::factory(10)->create();
    }
}
