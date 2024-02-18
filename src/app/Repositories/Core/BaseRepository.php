<?php

namespace App\Repositories\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** @todo improve error handling */
abstract class BaseRepository
{
    /**
     * @var Builder Builder instance linked with a specific model
     */
    private Builder $queryBuilder;



    abstract public function getModel() : Model;


    public function __construct()
    {
        $this->queryBuilder = $this->getModel()->newQuery();
    }

    public function findOneById(int $id) : Model
    {
        $queryBuilder = $this->queryBuilder->findOrFail($id);
        return $queryBuilder->find($id);
    }

    public function findAllPaginated(int $perPage, int $page): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate($perPage,['*'], null, $page);
    }

    public function store(Model $model): Model|Builder
    {
        return $this->queryBuilder->create($model->toArray());
    }

    public function update(Model $model) : Model
    {
        $this->queryBuilder->update($model->toArray());
        return $model->refresh();
    }

    public function destroy(Model $model): ?bool
    {
        return $model->delete();
    }
}
