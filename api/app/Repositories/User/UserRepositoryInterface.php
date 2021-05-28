<?php 

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface {

    public function subtractBalance(int $userId, float $value) : bool;

}