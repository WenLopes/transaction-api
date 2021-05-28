<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\TransactionStatusSeeder::class);

        $this->call(\Database\Seeders\NotificationStatusSeeder::class);

        $this->call(\Database\Seeders\UserSeeder::class);
    }
}
