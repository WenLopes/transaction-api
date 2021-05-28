<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('transaction_status')->delete();

        $allStatus = config('constants.transaction.status');

        foreach($allStatus as $description => $id){
            \DB::table('transaction_status')->insert([
                'id' => $id,
                'description' => $description
            ]);
        }

    }
}
