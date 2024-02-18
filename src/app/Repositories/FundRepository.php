<?php

namespace App\Repositories;

use App\Jobs\CheckDuplicatedFund;
use App\Models\Fund;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FundRepository extends BaseRepository
{
    public function getModel() : Model
    {
        return new Fund();
    }

    public function store(Model $model): Model|Builder
    {
        $model = parent::store($model);
        CheckDuplicatedFund::dispatch($model->id);
        return $model;
    }

    public function update(Model $model): Model
    {
        $model = parent::update($model);
        CheckDuplicatedFund::dispatch($model->id);
        return $model;
    }
}
