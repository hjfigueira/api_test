<?php

namespace App\Repositories\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    private Model $modelClass;


    public function __construct(Model $modelClass)
    {
        $this->modelClass   = $modelClass;
    }

    public function getQuery(): Builder
    {
        return $this->modelClass->newQuery();
    }

    public function getModel() : Model
    {
        return ($this->modelClass)::newModelInstance();
    }


    public function findOneById(int $id) : Model
    {
        $queryBuilder = $this->getQuery();
        return $queryBuilder->findOrFail($id);
    }


    public function findAllPaginated(int $perPage, int $page): LengthAwarePaginator
    {
        return $this->getQuery()->paginate($perPage, ['*'], 'page', $page);
    }


    public function store(Model $model): Model
    {
        $model->save();
        return $model->refresh();
    }


    public function update(Model $model) : Model
    {
        $model->update();
        return $model->refresh();
    }


    public function destroy(Model $model): ?bool
    {
        return $model->delete();
    }
}
