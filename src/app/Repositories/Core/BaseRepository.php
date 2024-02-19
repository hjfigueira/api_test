<?php

namespace App\Repositories\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Builder $queryBuilder;

    private Model $modelClass;


    public function __construct(Model $modelClass)
    {
        $this->modelClass   = $modelClass;
        $this->queryBuilder = $this->modelClass->newQuery();
    }


    public function getModel() : Model
    {
        return new $this->modelClass;
    }


    public function findOneById(int $id) : Model
    {
        $queryBuilder = $this->queryBuilder->findOrFail($id);
        return $queryBuilder->find($id);
    }


    public function findAllPaginated(int $perPage, int $page): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate($perPage, ['*'], 'page', $page);
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
