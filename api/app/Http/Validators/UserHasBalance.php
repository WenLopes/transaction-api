<?php 

namespace App\Http\Validators;

use App\Repositories\User\UserRepositoryInterface;

class UserHasBalance {

    /** @var UserRepositoryInterface */
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function passes($attribute, $value, $parameters, $validator)
    {
        $user = $this->userRepo->findById( $value );

        if(!$user){
            return false;
        }

        $transaction_value = getProperty( $validator->getData(), 'value' );
        
        if(!$transaction_value){
            return false;
        }

        return $user->balance >= $transaction_value;
    }
}