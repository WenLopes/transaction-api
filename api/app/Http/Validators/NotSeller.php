<?php 

namespace App\Http\Validators;

use App\Repositories\User\UserRepositoryInterface;

class NotSeller {

    /** @var UserRepositoryInterface */
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function passes($attribute, $value)
    {
        $user = $this->userRepo->findById( $value );

        if(!$user){
            return false;
        }

        if($user->is_seller){
            return false;
        }

        return true;
    }
}