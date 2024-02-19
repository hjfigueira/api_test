<?php

namespace App\Repositories;

use App\Http\Interfaces\ApiFilterableRepository;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Repository class for the Fund
 */
class FundDuplicatesRepository extends BaseRepository implements ApiFilterableRepository
{

    public function getFilterableQuery(): Builder
    {
        return $this->queryBuilder;
    }
}
