<?php 

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find user active by id.
     *
     * @param int $user
     * @param array $columns
     * @param array $relations
     * @return User
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = []
    ): ?Model {
        return $this->model->select($columns)->with($relations)->active()->find($modelId);
    }

    /**
     * Add value to the user's balance
     * @param 
     */
    public function addBalance(int $userId, float $value) : bool
    {
        $user = $this->findById($userId);
        return $this->update($userId, [
            'balance' => ($user->balance += $value)
        ]);
    }
    
    /**
     * Substract value to the user's balance
     * @param int $userId
     * @param float $value
     * @return bool
     */
    public function subtractBalance(int $userId, float $value) : bool
    {
        $user = $this->findById($userId);

        if ($value > $user->balance) {
            throw new \Exception('Value greater than available user balance');
        }

        return $this->update($userId, [
            'balance' => ($user->balance -= $value)
        ]);
    }
}