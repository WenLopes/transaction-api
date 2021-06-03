<?php

namespace Tests\Unit\Repositories;

use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @var UserRepository */
    protected $userRepo;

    protected function setUp() : void
    {
        parent::setUp();
        $this->userRepo = app(UserRepository::class);
    }

    /**
    * @test
    */
    public function test_should_not_create_users_with_duplicate_document()
    {
        $randomString = "duplicate".strtotime(now());
        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/UQ_Users_Document/i");

        $originalUser = User::factory()->create();
        $this->userRepo->create([
            'name' => 'Duplicated user',
            'document' => $originalUser->document,
            'email' => str_shuffle($randomString)."@mail.com.br",
            'password' => 'password'
        ]);
    }

    /**
    * @test
    */
    public function test_should_not_create_users_with_duplicate_email()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/UQ_Users_Email/i");
        
        $originalUser = User::factory()->create();
        $this->userRepo->create([
            'name' => 'Duplicated user',
            'email' => $originalUser->email,
            'document' => '00000000000',
            'password' => 'password'
        ]);
    }

    /**
     * @test
     */
    public function test_should_not_subtract_balance_if_the_value_is_greater_than_it()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Value greater than available user balance");
        $user  = User::factory()->create([
            'balance' => 100
        ]);
        $this->userRepo->subtractBalance($user->id, 1000);
    }

    /**
     * @test
     */
    public function test_should_subtract_balance()
    {
        $user  = User::factory()->create([
            'balance' => 1000
        ]);

        $this->assertTrue($this->userRepo->subtractBalance($user->id, 100));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'balance' => 900
        ]);
    }

    /**
     * @test
     */
    public function test_should_add_balance()
    {
        $user  = User::factory()->create([
            'balance' => 100
        ]);

        $this->assertTrue($this->userRepo->addBalance($user->id, 100));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'balance' => 200
        ]);
    }
}
