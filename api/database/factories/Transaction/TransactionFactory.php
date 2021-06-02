<?php

namespace Database\Factories\Transaction;

use App\Models\Transaction\Transaction;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payer_id' => function(){
                return User::factory()->create(['is_seller' => false])->id;
            },
            'payee_id' => function(){
                return User::factory()->create()->id;
            },
            'value' => 0.1,
            'transaction_status_id' => config('constants.transaction.status.WAITING')
        ];
    }
}
