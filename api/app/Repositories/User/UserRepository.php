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

    public function subtractBalance(int $user_id, float $value) : bool
    {
        $user = $this->findById($user_id);

        if( !$user ){
            return false;
        }

        if( $user->balance < $value ){
            return false;
        }

        $new_balance = ($user->balance - $value);

        return $this->update($user_id, [
            'balance' => $new_balance
        ]);
    }
}