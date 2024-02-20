<?php

namespace App\Repositories;

use App\Http\Interfaces\ApiFilterableRepository;
use App\Models\Fund;
use App\Models\FundDuplicatesCandidate;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Repository class for the Fund
 */
class FundDuplicatesRepository extends BaseRepository implements ApiFilterableRepository
{

    public function getFilterableQuery(): Builder
    {
        return $this->getQuery();
    }

    public function insertPossibleDuplicate(Fund $parent, Fund $duplicate): Model
    {
        /** @var FundDuplicatesCandidate $model */
        $model = $this->getModel();
        $model->parent()->associate($parent);
        $model->duplicate()->associate($duplicate);
        return $this->store($model);
    }

    /**
     * Method to check if the inverse relationship already exists, this is important so we don't
     * store redundant data.
     *
     * @param Fund $parent
     * @param Fund $duplicate
     * @return bool
     */
    public function hasInverseRelation(Fund $parent, Fund $duplicate) : bool
    {
        $parentId       = $parent->id;
        $duplicateId    = $duplicate->id;

        return $this->getQuery()
            ->where('parent_id', $duplicateId)
            ->where('duplicate_id', $parentId)
            ->where('resolved', false)
            ->exists();
    }
}
