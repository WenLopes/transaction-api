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
}