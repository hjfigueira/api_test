<?php

namespace App\Repositories;

use App\Jobs\SingleCheckDuplicatedFund;
use App\Models\Fund;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Repository class for the Fund
 */
class FundRepository extends BaseRepository
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
}
