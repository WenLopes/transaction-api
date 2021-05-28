<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isSeller = $this->faker->boolean(40);
        $document = $this->generateDocument($isSeller);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail,
            'document' => $document,
            'is_seller' => $isSeller,
            'password' => bcrypt('pass123'),
            'balance' => $this->faker->randomFloat(2, 10.00, 30000.00),
            'active' => $this->faker->boolean(80)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [];
        });
    }

    /**
     * Generate random document
     * @param bool $isSeller
     * @return string
     */
    private function generateDocument(bool $isSeller) : string
    {
        if($isSeller){
            return $this->formatDocumentString($this->faker->unique()->cnpj);
        }
        return $this->formatDocumentString($this->faker->unique()->cpf);
    }

    /**
     * Remove all punctuation from cnpj string
     * @param string $cnpj
     * @return string
     */
    private function formatDocumentString(string $document) : string
    {
        $document = str_replace(".", "", $document);
        $document = str_replace("/", "", $document);
        $document = str_replace("-", "", $document);
        return $document;
    }
}
