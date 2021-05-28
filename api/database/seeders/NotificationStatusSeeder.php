<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('notification_status')->delete();

        $allStatus = config('constants.notification.status');

        foreach($allStatus as $description => $id){
            \DB::table('notification_status')->insert([
                'id'          => $id,
                'description' => $description
            ]);
        }

    }
}
