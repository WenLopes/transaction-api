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
}
