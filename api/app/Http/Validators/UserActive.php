<?php

namespace App\Http\Validators;

use App\Repositories\User\UserRepositoryInterface;

class UserActive
{

    /** @var UserRepositoryInterface */
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function passes($attribute, $value): bool
    {
        $user = $this->userRepo->findById($value);

        if (!$user) {
            return false;
        }

        return true;
    }
}
