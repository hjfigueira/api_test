<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

/** @todo improve error handling */
abstract class Repository
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

    public function findAllPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate($perPage);
    }

    public function store(Model $model): Model|Builder
    {
        $this->queryBuilder->create($model->toArray());
        return $model->refresh();
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
