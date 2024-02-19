<?php

namespace App\Repositories;

use App\Http\Interfaces\ApiFilterableRepository;
use App\Jobs\SingleCheckDuplicatedFund;
use App\Models\Fund;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Repository class for the Fund
 */
class FundRepository extends BaseRepository implements ApiFilterableRepository
{

    public function store(Model $model): Model
    {
        /** @var Fund $model */
        $model = parent::store($model);
        SingleCheckDuplicatedFund::dispatch($model->id);
        return $model;
    }


    public function update(Model $model): Model
    {
        /** @var Fund $model */
        $model = parent::update($model);
        SingleCheckDuplicatedFund::dispatch($model->id);
        return $model;
    }

    public function getFilterableQuery(): Builder
    {
        return $this->queryBuilder;
    }
}
